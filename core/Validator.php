<?php

namespace app\core;

use PDO;

class Validator
{
    private $data;
    private $errors = [];
    private static $rules = ['required', 'email', 'min', 'max', 'array', 'confirmed', 'unique'];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function validate($rules)
    {
        foreach ($rules as $field => $ruleArray) {
            $value = $this->data[$field] ?? null;

            foreach ($ruleArray as $rule) {
                if (is_string($rule)) {
                    if (in_array($rule, self::$rules)) {
                        call_user_func([$this, $rule], $field, $value);
                    }
                } elseif (is_array($rule)) {
                    $ruleName = $rule[0];
                    $ruleParams = array_slice($rule, 1);
                    if (in_array($ruleName, self::$rules)) {
                        call_user_func_array([$this, $ruleName], array_merge([$field, $value], $ruleParams));
                    }
                }
            }
        }

        return $this;
    }


    private function required($field, $value)
    {
        if (empty($value)) {
            $this->addError($field, "$field is required");
        }
    }

    private function email($field, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, "$field must be a valid email");
        }
    }

    private function min($field, $value, $threshold)
    {
        if (strlen($value) < $threshold) {
            $this->addError($field, "$field must be at least $threshold characters long");
        }
    }

    private function max($field, $value, $threshold)
    {
        if (strlen($value) > $threshold) {
            $this->addError($field, "$field must not exceed $threshold characters");
        }
    }

    private function unique($field, $value, $table, $column, $exceptionId = null)
    {
        $exceptionQuery = $exceptionId ? " AND id != $exceptionId" : "";
        $statement = pdo()->prepare("SELECT * FROM $table WHERE $column = $value $exceptionQuery");
        $statement->execute();
        $check = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (isset($check)) {
            $this->addError($field, "This $field is already in our database");
        }
    }

    private function addError($field, $message)
    {
        $this->errors[$field][] = $message;
    }

    private function array($field, $value)
    {
        if (!is_array($value)) {
            $this->addError($field, "$field must be an array");
        }
    }

    private function confirmed($field, $value)
    {
        $confirmedField = $field . '_confirmation';
        if ($this->data[$confirmedField] !== $value) {
            $this->addError($field, "{$field} does not match the confirmation");
        }
    }

    public function passes()
    {
        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
