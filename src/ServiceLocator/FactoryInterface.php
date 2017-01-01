<?php

namespace Faulancer\ServiceLocator;

/**
 * Interface FactoryInterface
 *
 * @package Faulancer\ServiceLocator
 * @author Florian Knapp <office@florianknapp.de>
 */
interface FactoryInterface
{

    /**
     * Create the factory for the requested service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return object
     */
    public function createService(ServiceLocatorInterface $serviceLocator);

}