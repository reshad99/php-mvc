<?php

use app\core\Database\Schema;
use app\core\Migration;

class m02_add_phone_to_users implements Migration
{
    public function up()
    {
        $schema = new Schema('users', false, true);
        $schema->string('phone', 255, true);
        pdo()->exec($schema->getSql());
    }

    public function down()
    {
        $schema = new Schema('users', false, true);
        $schema->dropColumn('phone');
        pdo()->exec($schema->getSql());
    }
}
