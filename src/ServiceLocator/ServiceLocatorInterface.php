<?php
/**
 * Interface ServiceLocatorInterface | ServiceLocatorInterface.php
 *
 * @package Faulancer\ServiceLocator
 * @author Florian Knapp <office@florianknapp.de>
 *
 */
namespace Faulancer\ServiceLocator;

/**
 * Interface ServiceLocatorInterface
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
     * @return ServiceInterface
     */
    public function get(string $service = '');

}