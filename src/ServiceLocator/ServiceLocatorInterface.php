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

    public function get(string $service);

}