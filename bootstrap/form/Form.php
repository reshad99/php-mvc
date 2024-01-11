<?php

namespace app\bootstrap\form;

use app\core\Model;

class Form
{
    public  $model;
    public array $errors;
    public function __construct($model, array $errors)
    {
        $this->model = $model;
        $this->errors = $errors;
    }

    public static function begin($action, $method, $model, $errors)
    {
        try {
            echo sprintf('<form action="%s" method="%s">', $action, $method);
            return new Form($model, $errors);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function end()
    {
        echo '</form>';
    }

    public function field($title, $field, $type = 'text')
    {
        try {
            $html = sprintf(
                '<label class="form-label">%s</label>
                <input type="%s" name="%s" value="%s" class="form-control" id="%s">',
                $title,
                $type,
                $field,
                htmlspecialchars($this->model->$field ?? ''),
                $field
            );

            if (isset($this->errors[$field])) {
                $html .= '<div class="badge bg-danger invalid-feedback">' . htmlspecialchars($this->errors[$field][0]) . '</div>';
            }

            return $html;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
