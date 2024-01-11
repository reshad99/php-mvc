<?php

namespace app\requests;

use app\core\Request;

class ContactRequest extends Request
{
    public function rules()
    {
        return [
            'subject' => ['required'],
            'email' => ['required', 'email'],
            'message' => ['required']
        ];
    }
}
