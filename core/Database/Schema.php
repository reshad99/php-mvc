<?php


namespace app\core\Database;

use PDO;

class Schema
{
    public string $SQL = "";
    public array $columns = [];
    public array $executedColumns = [];
    public bool $dropTable;
    public bool $alter;

    public function __construct($tableName, $dropTable = false, $alter = false)
    {
        $this->dropTable = $dropTable;
        $this->alter = $alter;

        if ($this->alter === true) {
            $this->SQL .= "ALTER TABLE $tableName ";
        } else {
            $this->SQL .= !$this->dropTable ? "CREATE TABLE $tableName " : "DROP TABLE $tableName";
        }
    }

    public function id()
    {
        $type = "INT AUTO_INCREMENT PRIMARY KEY";
        $this->addColumn("id", $type, false);
    }

    public function dropColumn($columnName)
    {
        $this->SQL .= "DROP COLUMN $columnName ";
    }

    public function string($column, $maxLength, $nullable = true)
    {
        $type = "VARCHAR($maxLength)";
        $this->addColumn($column, $type, $nullable);
    }

    public function int($column, $maxLength = null, $nullable = true, $unsigned = true)
    {
        $unsigned = $unsigned ? 'UNSIGNED' : "";
        $type = "INT $unsigned {$this->integerCheck($column,$maxLength)}";
        $this->addColumn($column, $type, $nullable);
    }

    public function tinyInt($column, $maxLength = null, $nullable = true, $unsigned = true)
    {
        $unsigned = $unsigned ? 'UNSIGNED' : "";
        $type = "TINYINT $unsigned {$this->integerCheck($column,$maxLength)}";
        $this->addColumn($column, $type, $nullable);
    }

    public function timeStamps()
    {
        $type = "TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
        $this->addColumn('created_at', $type, true);
        $this->addColumn('updated_at', $type, true);
    }

    public function getSql()
    {
        $columnsSql = $this->executeColumns();
        $this->SQL .= $columnsSql;

        if ($this->alter === false && $this->dropTable === false)
            $this->SQL .= " ENGINE=InnoDB";

        return $this->SQL;
    }

    private function executeColumns()
    {
        $addColumn = $this->alter ? "ADD COLUMN" : "";
        foreach ($this->columns as $name => $type) {
            $this->executedColumns[] = "$addColumn $name {$type['type']}";
        }

        $columns = implode(', ', $this->executedColumns);

        if ($this->alter || $this->dropTable)
            return $columns;

        $sql = "(" . $columns . ")";
        return $sql;
    }

    private function addColumn($column, $type, $nullable = true)
    {
        $this->columns[$column] = ['type' => "$type {$this->nullable($nullable)}"];
    }

    private function nullable(bool $nullable)
    {
        return $nullable ? 'NULL' : "NOT NULL";
    }

    private function integerCheck($column, $maxLength)
    {
        return $maxLength != null ? "CHECK ($column <= $maxLength)" : "";
    }
}
