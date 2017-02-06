<?php
/**
 * Class AuthenticatorServiceFactory | AuthenticatorServiceFactory.php
 * @package Faulancer\Service\Factory
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Service\Factory;

use Faulancer\Service\AuthenticatorService;
use Faulancer\Service\Config;
use Faulancer\Controller\Controller;
use Faulancer\Service\ControllerService;
use Faulancer\ServiceLocator\FactoryInterface;
use Faulancer\ServiceLocator\ServiceLocatorInterface;

/**
 * Class AuthenticatorServiceFactory
 */
class AuthenticatorServiceFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AuthenticatorService
     * @codeCoverageIgnore
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var Controller $controller */
        $controller = $serviceLocator->get(ControllerService::class);

        /** @var Config $config */
        $config = $serviceLocator->get(Config::class);

        return new AuthenticatorService($controller, $config);
    }

}