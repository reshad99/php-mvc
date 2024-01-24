<?php

namespace app\models;

use app\core\Model;

class User extends Model
{
    protected $fillable = ['full_name', 'email', 'password'];
    protected $tableName = "users";
}
