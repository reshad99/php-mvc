<?php

$files = scandir(__DIR__ . '/../config');

foreach (array_slice($files, 2) as $key => $file) {
    require_once __DIR__ . "/../config/$file";
}
