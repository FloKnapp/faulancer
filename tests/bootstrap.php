<?php

require_once __DIR__ . '/../vendor/autoload.php';

/** @var \Faulancer\Service\Config $config */
$config = \Faulancer\ServiceLocator\ServiceLocator::instance()->get(\Faulancer\Service\Config::class);

$config->set(require __DIR__ . '/Fixture/config/app.conf.php');