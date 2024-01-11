<?php

namespace app\controllers;

use app\core\Application;
use app\core\Request;
use app\core\Session;
use app\core\Validator;
use app\models\User;
use app\requests\RegisterRequest;

class AuthController extends Controller
{
    public function login()
    {
        return $this->view('front.auth.login');
    }

    public function register()
    {
        $user = Session::check('user') ? Session::get('user') : new User;
        return $this->view('front.auth.register', compact('user'));
    }

    public function tryRegister(RegisterRequest $request)
    {
        $body = $request->getBody();
        $user = new User;
        $user->fill($body);
        Session::flash('user', $user);
        $request->validate($body);
    }
}
