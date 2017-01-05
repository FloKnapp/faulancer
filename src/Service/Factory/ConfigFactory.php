<?php

namespace Faulancer\Service\Factory;

use Faulancer\Service\Config;
use Faulancer\ServiceLocator\FactoryInterface;
use Faulancer\ServiceLocator\ServiceLocatorInterface;

/**
 * File Factory.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class ConfigFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Config();
    }

}