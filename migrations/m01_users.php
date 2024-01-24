<?php

use app\core\Database\Schema;
use app\core\Migration;

class m01_users implements Migration
{
    public function up()
    {
        $schema = new Schema('users');
        $schema->id();
        $schema->string('full_name', '255', true);
        $schema->string('email', '255', true);
        $schema->string('password', '255', true);
        $schema->timeStamps();
        pdo()->exec($schema->getSql());
    }

    public function down()
    {
        $schema = new Schema('users', true);
        pdo()->exec($schema->getSql());
    }
}
