<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dbManager = \Faulancer\ServiceLocator\ServiceLocator::instance()->get(\Faulancer\Service\DbService::class);

/** @var \Faulancer\Session\SessionManager $sessionManager */
$sessionManager = \Faulancer\ServiceLocator\ServiceLocator::instance()->get(\Faulancer\Session\SessionManager::class);
$sessionManager->startSession();

/** @var \Faulancer\Service\Config $config */
$config = \Faulancer\ServiceLocator\ServiceLocator::instance()->get(\Faulancer\Service\Config::class);

$config->set(require __DIR__ . '/Fixture/config/app.conf.php');