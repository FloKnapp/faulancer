<?php

namespace Faulancer\Service\Factory;

use Faulancer\Controller\Controller;
use Faulancer\Service\AuthenticatorService;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\FactoryInterface;
use Faulancer\ServiceLocator\ServiceLocatorInterface;

/**
 * Class AuthenticatorServiceFactory
 *
 * @package Faulancer\Service\Factory
 * @author  Florian Knapp <office@florianknapp.de>
 */
class AuthenticatorServiceFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return AuthenticatorService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var Controller $controller */
        $controller = $serviceLocator->get(Controller::class);

        /** @var Config $config */
        $config = $serviceLocator->get(Config::class);

        return new AuthenticatorService($controller, $config);
    }

}