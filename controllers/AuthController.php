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
        try {
            $body = $request->getBody();
            $request->validate($body);

            $user = new User;
            $user->fill($body);
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);
            $user->save();
            Session::flash('user', $user);
            return json_encode($user);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
