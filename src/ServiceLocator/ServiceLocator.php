<?php

namespace Faulancer\ServiceLocator;

use Faulancer\Exception\FactoryMayIncompatibleException;
use Faulancer\Exception\ServiceNotFoundException;

/**
 * Class ServiceLocator
 *
 * @package Faulancer\ServiceLocator
 * @author Florian Knapp <office@florianknapp.de>
 */
class ServiceLocator implements ServiceLocatorInterface {

    /** @var ServiceLocator */
    private static $instance = null;

    private static $services = [];

    /**
     * ServiceLocator private constructor.
     */
    private function __construct() {}

    /**
     * @return ServiceLocator
     */
    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Try to get the service.
     * Returns always a new instance of the service/factory.
     * Can be configured in future to share the instance.
     *
     * @param string  $service
     * @param boolean $shared
     * @return FactoryInterface
     * @throws ServiceNotFoundException
     */
    public function get(string $service, $shared = true)
    {
        if ($shared && isset(self::$services[$service])) {
            return self::$services[$service];
        }

        try {
            $class = $this->getFactory($service)->createService($this);
        } catch (FactoryMayIncompatibleException $e) {
            $class = $this->getService($service);
        } catch (ServiceNotFoundException $e) {
            return null;
        }

        if ($shared) {
            self::$services[$service] = $service;
        }

        return $class;

    }

    private function getService(string $service)
    {
        if (class_exists($service)) {
            return new $service();
        }

        throw new ServiceNotFoundException();
    }

    /**
     * Check if we have a factory for this service
     *
     * @param string $service
     * @return FactoryInterface|null
     * @throws FactoryMayIncompatibleException
     */
    private function getFactory(string $service)
    {
        $parts     = explode('\\', $service);
        $className = array_splice($parts, count($parts) - 1, 1);
        $class     = implode('\\', $parts) . '\\Factory\\' . $className[0] . 'Factory';

        if (class_exists($class) && in_array(FactoryInterface::class, class_implements($class, true))) {
            return new $class();
        }

        throw new FactoryMayIncompatibleException();
    }

}