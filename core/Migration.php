<?php

namespace app\core;

interface Migration
{
    public function up();
    public function down();
}
