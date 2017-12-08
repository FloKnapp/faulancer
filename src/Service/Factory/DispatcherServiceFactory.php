<?php

namespace Faulancer\Service\Factory;

use Faulancer\Http\Request;
use Faulancer\Service\Config;
use Faulancer\Service\DispatcherService;
use Faulancer\Service\RequestService;
use Faulancer\ServiceLocator\FactoryInterface;
use Faulancer\ServiceLocator\ServiceLocatorInterface;

/**
 * Class DispatcherServiceFactory
 *
 * @package Faulancer\Service\Factory
 * @author  Florian Knapp <office@florianknapp.de>
 */
class DispatcherServiceFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return DispatcherService
     * @codeCoverageIgnore
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var Request $request */
        $request = $serviceLocator->get(RequestService::class);

        /** @var Config $config */
        $config  = $serviceLocator->get(Config::class);

        return new DispatcherService($request, $config);
    }

}