<?php
namespace php_rutils\test;

define('LIB_DIR', realpath(__DIR__.'/../..'));
ini_set('display_errors', true);
error_reporting(E_ALL | E_STRICT);
mb_internal_encoding('UTF-8');

spl_autoload_register(function($className) {
        $classPath = LIB_DIR. DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $className).'.php';
        if (is_file($classPath))
            require_once $classPath;
        else
            throw new \Exception("Wrong class path $classPath");
    });
