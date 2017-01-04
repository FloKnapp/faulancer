<?php

require_once __DIR__ . '/../vendor/autoload.php';

if (!defined('VIEWS_ROOT')) {
    define('VIEWS_ROOT', __DIR__ . '/Fixture/views');
}

if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', __DIR__ . '/Fixture');
}

if (!defined('APPLICATION_ROOT')) {
    define('APPLICATION_ROOT', PROJECT_ROOT . '/src');
}

if (!defined('NAMESPACE_PREFIX')) {
    define('NAMESPACE_PREFIX', 'Faulancer\Fixture');
}

if (!defined('TRANSLATION_ROOT')) {
    define('TRANSLATION_ROOT', APPLICATION_ROOT . '/translation');
}