<?php
/**
 * Factory for config service
 *
 * @package Faulancer\Service\Factory
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Service\Factory;

use Faulancer\Service\Config;
use Faulancer\ServiceLocator\FactoryInterface;
use Faulancer\ServiceLocator\ServiceLocatorInterface;

/**
 * Class ConfigFactory
 */
class ConfigFactory implements FactoryInterface
{

    /**
     * Create config service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Config
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Config();
    }

}