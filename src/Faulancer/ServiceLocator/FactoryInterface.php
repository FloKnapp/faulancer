<?php
/**
 * File FactoryInterface.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */

namespace Faulancer\ServiceLocator;

interface FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator);

}