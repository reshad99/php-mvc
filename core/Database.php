<?php

namespace app\core;

use PDO;

class Database
{
    public PDO $pdo;
    public function __construct()
    {
        $database = config('db.database');
        $host = config('db.host');
        $port = config('db.port');
        $databaseName = config('db.database_name');
        $dsn = $this->dsn($database, $host, $port, $databaseName);
        $this->pdo = new PDO($dsn, 'root', config('db.password'));
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function dsn(string $database, string $host, string $port, string $dbName)
    {
        return "$database:host=$host;port=$port;dbname=$dbName";
    }

    public function applyMigrations(string $direction = 'up' | 'down')
    {
        if ($direction === 'down') {
            $this->truncateMigrations();
        }

        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $files = scandir(Application::$rootDir . "/migrations");
        $toApplyMigrations = array_slice(array_diff($files, $appliedMigrations), 2);

        foreach ($toApplyMigrations as $key => $migration) {
            require_once Application::$rootDir . "/migrations/$migration";
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className;
            $instance->$direction();
            $newMigrations[] = $migration;
        }


        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("All migrations are applied");
        }
    }

    private function truncateMigrations()
    {
        $this->pdo->exec("TRUNCATE migrations");
    }

    private function saveMigrations(array $migrations)
    {
        $values = implode(",", array_map(fn ($m) => "('$m')", $migrations));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $values");
        $statement->execute();
    }

    public function createMigrationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB");
    }

    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    protected function log(string $message)
    {
        echo "[" . date('Y-m-d H:i:s') . "] " . $message . PHP_EOL;
    }
}
