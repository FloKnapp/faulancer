<?php

require_once __DIR__ . '/../vendor/autoload.php';

if (!defined('VIEWS_ROOT')) {
    define('VIEWS_ROOT', __DIR__ . '/Mocks/views');
}

if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', realpath(__DIR__ . '/../tests'));
}

if (!defined('APPLICATION_ROOT')) {
    define('APPLICATION_ROOT', 'Mocks');
}

if (!defined('NAMESPACE_PREFIX')) {
    define('NAMESPACE_PREFIX', 'Faulancer\Test\\');
}