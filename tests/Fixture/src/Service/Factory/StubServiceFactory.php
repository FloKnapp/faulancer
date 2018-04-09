<?php

namespace Faulancer\Fixture\Service\Factory;

use Faulancer\ServiceLocator\FactoryInterface;
use Faulancer\ServiceLocator\ServiceLocatorInterface;
use Faulancer\Fixture\Service\StubService;

/**
 * File StubFactory.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class StubServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dependency = 'here';
        return new StubService($dependency);
    }

}