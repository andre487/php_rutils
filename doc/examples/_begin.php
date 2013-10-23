<?php
namespace php_rutils\doc\examples;

define('LIB_DIR', realpath(__DIR__.'/../../..'));
define('CLI', php_sapi_name() == 'cli');
mb_internal_encoding('UTF-8');

if (CLI == false)
    header('Content-type: text/plain; charset=UTF-8');

spl_autoload_register(function($className) {
        $classPath = LIB_DIR. DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $className).'.php';
        if (is_file($classPath))
            require_once $classPath;
        else
            throw new \Exception("Wrong class path $classPath");
    });

ob_start();
