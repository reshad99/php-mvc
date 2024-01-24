<?php

namespace app\controllers;

use app\core\Application;
use app\core\Request;
use app\models\User;
use app\requests\ContactRequest;

class SiteController extends Controller
{
    public function index()
    {
        try {
            $name = 'Rashad';
            $user = new User;
            $users = $user->select()->get();
            return $this->view('front.home', compact('name', 'users'));
        } catch (\Exception $e) {
            throw $e;
        }
       
    }

    public function indexContact()
    {
        return $this->view('front.contact');
    }

    public function contactPost(ContactRequest $request)
    {
        //en son burda qaldiq request validate calistiracaksan
        $request->validate($request->getBody(), $request->rules());
        return 'Handling submitted data';
    }
}
