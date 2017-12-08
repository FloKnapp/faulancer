<?php

namespace Faulancer\Service\Factory;

use Faulancer\Service\AbstractControllerService;
use Faulancer\Service\AuthenticatorService;
use Faulancer\Service\Config;
use Faulancer\Controller\AbstractController;
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
        /** @var AbstractController $controller */
        $controller = $serviceLocator->get(AbstractControllerService::class);

        /** @var Config $config */
        $config = $serviceLocator->get(Config::class);

        return new AuthenticatorService($controller, $config);
    }

}