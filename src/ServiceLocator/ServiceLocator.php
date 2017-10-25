<?php

namespace Faulancer\ServiceLocator;

use Faulancer\Exception\FactoryMayIncompatibleException;
use Faulancer\Exception\ServiceNotFoundException;

/**
 * Class ServiceLocator | ServiceLocator.php
 *
 * @package Faulancer\ServiceLocator
 * @author Florian Knapp <office@florianknapp.de>
 */
class ServiceLocator implements ServiceLocatorInterface {

    /**
     * Holds the service locator instance
     * @var ServiceLocator
     */
    private static $instance = null;

    /**
     * Holds the requested services
     * @var array
     */
    private static $services = [];

    /** @var array */
    private static $backup = [];

    /**
     * ServiceLocator private constructor.
     */
    private function __construct() {}

    /**
     * Return the instance of the service locator
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
     * Returns per default always a new instance of the service/factory.
     *
     * @param string  $service
     * @param boolean $shared
     * @return ServiceInterface|FactoryInterface
     * @throws ServiceNotFoundException
     */
    public function get(string $service = '', $shared = true)
    {
        if (in_array($service, array_keys(self::$backup))) {

            $overriddenService = self::$services[$service];
            self::$services[$service] = self::$backup[$service];
            unset(self::$backup[$service]);
            return $overriddenService;

        }

        if ($shared && !empty(self::$services[$service])) {
            return self::$services[$service];
        }

        try {
            $class = $this->_getFactory($service)->createService($this);
        } catch (FactoryMayIncompatibleException $e) {
            $class = $this->_getService($service);
        }

        self::$services[$service] = $class;

        return $class;
    }

    /**
     * Get specific service by class name
     *
     * @param  string $service
     * @return mixed
     * @throws ServiceNotFoundException
     */
    private function _getService(string $service)
    {
        if (!class_exists($service)) {
            throw new ServiceNotFoundException($service . ' not found');
        }

        return new $service();
    }

    /**
     * Check if we have a factory for this service
     *
     * @param string $service
     * @return FactoryInterface|null
     * @throws FactoryMayIncompatibleException
     */
    private function _getFactory(string $service)
    {
        $parts     = explode('\\', $service);
        $className = array_splice($parts, count($parts) - 1, 1);
        $class     = implode('\\', $parts) . '\\Factory\\' . $className[0] . 'Factory';

        $isAutoDetected = class_exists($class) && in_array(FactoryInterface::class, class_implements($class));
        $isDirectAccess = class_exists($service) && in_array(FactoryInterface::class, class_implements($service));

        if ($isAutoDetected) {
            return new $class();
        }

        // This is a direct factory access
        if ($isDirectAccess) {
            return new $service();
        }

        throw new FactoryMayIncompatibleException();
    }

    /**
     * @param string           $name
     * @param ServiceInterface $service
     * @internal
     */
    public function set($name, $service)
    {
        if (isset(self::$services[$name])) {
            self::$backup[$name] = self::$services[$name];
        }

        self::$services[$name] = $service;
    }

    /**
     * Reset the service locators instance
     *
     * @internal
     */
    public static function destroy()
    {
        self::$instance = null;
    }

}