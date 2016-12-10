<?php

namespace Faulancer\ServiceLocator;

use Composer\Factory;
use Faulancer\Exception\FactoryMayIncompatibleException;
use Faulancer\Exception\ServiceNotFoundException;

/**
 * Class ServiceLocator
 *
 * @package Faulancer\ServiceLocator
 * @author Florian Knapp <office@florianknapp.de>
 */
class ServiceLocator implements ServiceLocatorInterface {

    /**
     * Try to get the service
     *
     * @param string $service
     * @return FactoryInterface
     * @throws ServiceNotFoundException
     */
    public function get(string $service)
    {
        try {
            return $this->getFactory($service)->createService($this);
        } catch (FactoryMayIncompatibleException $e) {}

        if (class_exists($service)) {
            return new $service();
        }

        throw new ServiceNotFoundException('Service "' . $service . '" cannot be found');
    }

    /**
     * Check if we have a factory for this service
     *
     * @param string $service
     * @return boolean|FactoryInterface
     * @throws FactoryMayIncompatibleException
     */
    private function getFactory(string $service)
    {
        $parts     = explode('\\', $service);
        $className = array_splice($parts, count($parts) - 1, 1);
        $class     = implode('\\', $parts) . '\\Factory\\' . $className[0] . 'Factory';

        if (!in_array(FactoryInterface::class, class_implements($class, true))) {
            throw new FactoryMayIncompatibleException('Factory doesn\'t implement FactoryInterface');
        }

        if (class_exists($class)) {
            return new $class();
        }

        return false;
    }

}