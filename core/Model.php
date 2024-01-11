<?php

namespace app\core;

abstract class Model
{
    protected $attributes = [];

    // Filled özellikleri tanımlayın
    protected $fillable = [];

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

    // Ek olarak, mevcut özellikleri almak için bir metod
    public function getAttributes()
    {
        return $this->attributes;
    }
}
