<?php

namespace Faulancer\ServiceLocator;

/**
 * Interface ServiceLocatorAwareInterface | ServiceLocatorAwareInterface.php
 *
 * @package Faulancer\ServiceLocator
 * @author  Florian Knapp <office@florianknapp.de>
 */
interface ServiceLocatorAwareInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator);

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator();

}