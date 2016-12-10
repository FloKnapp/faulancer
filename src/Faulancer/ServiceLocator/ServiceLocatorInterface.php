<?php

namespace Faulancer\ServiceLocator;

/**
 * Interface ServiceLocatorInterface
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
interface ServiceLocatorInterface
{

    public function get(string $service);

}