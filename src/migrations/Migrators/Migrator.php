<?php

namespace matfish\Blogify\migrations\Migrators;

abstract class Migrator
{
    abstract public static function add() : bool;
    abstract public static function remove() : bool;

    protected function removeSection($handle) {

    }

    protected static function log($message) {
        echo $message . PHP_EOL;
    }
}