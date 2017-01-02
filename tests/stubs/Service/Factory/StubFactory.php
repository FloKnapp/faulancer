<?php

namespace stubs\Service\Factory;
use Faulancer\ServiceLocator\FactoryInterface;
use Faulancer\ServiceLocator\ServiceLocatorInterface;
use stubs\Service\StubService;


/**
 * File StubFactory.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class StubFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new StubService();
    }

}