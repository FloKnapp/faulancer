<?php

namespace Faulancer\ServiceLocator;

/**
 * Interface ServiceLocatorInterface
 *
 * @package Faulancer\ServiceLocator
 * @author Florian Knapp <office@florianknapp.de>
 *
 */
interface ServiceLocatorInterface
{

    /**
     * Get a specific service
     *
     * Call example -------------------------------------------------
     *
     *   $service = ServiceLocator::instance()->get(Service::class);
     *
     * or from controller
     *
     *   $service = $this->getServiceLocator()->get(Service::class);
     *
     * --------------------------------------------------------------
     *
     * @param string $service
     *
     * @return object
     */
    public function get(string $service);

}