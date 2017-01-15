<?php

require_once __DIR__ . '/../vendor/autoload.php';

/** @var \Faulancer\Service\Config $config */
$config = \Faulancer\ServiceLocator\ServiceLocator::instance()->get(\Faulancer\Service\Config::class);

$config->set('viewsRoot',       __DIR__ . '/Fixture/views');
$config->set('projectRoot',     __DIR__ . '/Fixture');
$config->set('applicationRoot', __DIR__ . '/Fixture/src');
$config->set('namespacePrefix', 'Faulancer\Fixture');
$config->set('translationFile', __DIR__ .'/Fixture/config/translation.php');
$config->set('routeFile',       __DIR__ . '/Fixture/config/routes.php');

$testData = ['layer' => ['layer2' => 'test']];
$config->set('recursiveTest', $testData);