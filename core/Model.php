<?php

namespace app\core;

use PDO;

abstract class Model
{
    protected $attributes = [];
    protected $primaryKey = "id";
    private $query;

    // Filled özellikleri tanımlayın
    protected $fillable = [];

    protected $tableName = "";

    public function __set($name, $value)
    {
        if (in_array($name, $this->fillable)) {
            $this->attributes[$name] = $value;
        }
    }

    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function fill(array $data)
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->attributes[$key] = htmlspecialchars($value);
            }
        }
    }

    public function save()
    {
        $columns = implode(", ", array_keys($this->attributes));
        $placeholders = implode(", ", array_map(fn ($attr) => ":$attr", array_keys($this->attributes)));

        $sql = "INSERT INTO $this->tableName ($columns) VALUES ($placeholders)";
        $statement = pdo()->prepare($sql);

        foreach ($this->attributes as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        $statement->execute();
    }

    public function where($column, $value, $operator = '=')
    {
        $this->query['where'][] = "$column $operator ?";
        $this->query['params'][] = $value;
        return $this;
    }

    public function select($columns = '*')
    {
        $this->query['select'] = $columns;
        return $this;
    }

    public function orderBy($column, $direction = 'ASC' | 'DESC')
    {
        $this->query['order'] = ['column' => $column, 'direction' => $direction];
        return $this;
    }

    public function get()
    {
        $sql = $this->buildSelectQuery();
        $statement = pdo()->prepare($sql);
        $statement->execute($this->query['params'] ?? null);
        return $statement->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    public function first()
    {
        $result = $this->get();
        return $result[0] ?? null;
    }

    public function find($id)
    {
        $this->query['where'][] = "{$this->primaryKey} = ?";
        $this->query['params'][] = $id;

        return $this->first();
    }

    private function buildSelectQuery()
    {
        $sql = "SELECT {$this->query['select']} FROM {$this->tableName}";

        if (!empty($this->query['where'])) {
            $sql .= " WHERE " . implode(" AND ", $this->query['where']);
        }

        if (!empty($this->query['order'])) {
            $order = $this->query['order'];
            $sql .= " ORDER BY {$order['column']} {$order['direction']}";
        }

        return $sql;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }
}
