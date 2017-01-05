<?php

require_once __DIR__ . '/../vendor/autoload.php';

/** @var \Faulancer\Service\Config $config */
$config = \Faulancer\ServiceLocator\ServiceLocator::instance()->get(\Faulancer\Service\Config::class);

$config->set('viewsRoot', __DIR__ . '/Fixture/views');
$config->set('projectRoot', __DIR__ . '/Fixture');
$config->set('applicationRoot', $config->get('projectRoot') . '/src');
$config->set('namespacePrefix', 'Faulancer\Fixture');
$config->set('translationRoot', $config->get('applicationRoot') . '/translation');
$config->set('routeCacheFile', __DIR__ . '/Fixture/cache/routes.json');