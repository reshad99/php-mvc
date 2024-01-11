<?php

namespace app\requests;

use app\core\Request;

class RegisterRequest extends Request
{
    public function rules()
    {
        return [
            'full_name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min' => 6, 'confirmed']
        ];
    }
}
