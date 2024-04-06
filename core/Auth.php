<?php

namespace app\core;

use app\core\Exceptions\AuthenticationException;
use app\core\Exceptions\ModelNotFoundException;
use Exception;

class Auth
{
    private static $instance = null;
    protected static array $guards;
    protected static string $guard;

    private function __construct() {
        self::$guard = config('auth.default_guard');
        self::$guards = config('auth.guards');
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Auth();
        }
        return self::$instance;
    }

    public static function attempt(array $credentials, bool $rememberMe = false): bool
    {
        try {
            if (in_array(self::$guard, self::$guards)) {
                $currentGuard = self::$guards[self::$guard];

                switch ($currentGuard['driver']) {
                    case 'session':
                        self::sessionAuthentication($credentials, $currentGuard, $rememberMe);
                        break;

                    default:
                        self::sessionAuthentication($credentials, $currentGuard, $rememberMe);
                        break;
                }
            } else {
                throw new Exception("Guard is not found");
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function setGuard(string $guard)
    {
        self::$guard = $guard;
        return self::getInstance();
    }

    public static function check()
    {
        $sessionUser = Session::check('user');
        $cookieUser = Cookie::get('user');

        if (isset($sessionUser)) {
            return $sessionUser->id;
        }

        if (isset($cookieUser)) {
            return $cookieUser->id;
        }

        return false;
    }

    public static function user(): Model|null
    {
        $userId = self::check();
        if ($userId) {
            $currentGuard = self::$guards[self::$guard];
            $checkUser = self::getUserFromDB($currentGuard['class'], 'id', $userId);

            if ($checkUser) {
                return $checkUser;
            }
        }
        return null;
    }

    private static function sessionAuthentication(array $credentials, $currentGuard, $rememberMe)
    {
        foreach ($credentials as $column => $value) {
            if ($column !== 'password') {
                $currentGuard = self::$guards[self::$guard];
                $user = self::getUserFromDB($currentGuard['class'], $column, $value);

                if (isset($user)) {
                    $passwordCheck = password_verify($credentials['password'], $user->password);

                    if ($passwordCheck === true) {
                        self::saveUserToSession($user);

                        if ($rememberMe) {
                            self::saveUserToCookie($user);
                        }
                    } else {
                        throw new AuthenticationException("{$credentials[0]} or {$credentials[1]} is wrong");
                    }
                } else {
                    throw new AuthenticationException("User not found");
                }
            }
        }
    }

    private static function getUserFromDB(string $className, $primaryKey, $id)
    {
        $class = new $className();
        $class->primaryKey = $primaryKey;
        $checkUser = call_user_func([$class, 'find'], $id);
        return isset($checkUser) ? $checkUser : null;
    }


    private static function saveUserToSession($user)
    {
        Session::flash('user', $user);
    }

    private static function saveUserToCookie($user)
    {
        Cookie::set('user', $user);
    }
}
