<?php

namespace Faulancer\Event;

use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class Observer
 * @package Faulancer\Event
 * @author  Florian Knapp <office@florianknapp.de>
 */
class Observer
{

    /** @var self */
    protected static $instance;

    /** @var AbstractListener[] */
    protected static $listener = [];

    /** @var bool */
    protected static $missingConfig = false;

    /**
     * Observer constructor (private).
     */
    private function __construct() {}

    /**
     * Yeah... singleton... i know
     *
     * @return self
     */
    public static function instance()
    {
        if (!self::$instance) {

            /** @var Config $config */
            $config = ServiceLocator::instance()->get(Config::class);
            self::$listener = $config->get('eventListener');
            self::$instance = new self();

            if (!self::$listener) {
                self::$missingConfig = true;
            }

        }

        return self::$instance;
    }

    /**
     * Trigger listeners if registered for the type
     *
     * @param AbstractEvent $event
     *
     * @return bool
     */
    public function trigger(AbstractEvent $event)
    {
        if (self::$missingConfig) {
            return false;
        }

        foreach (self::$listener as $typeName => $listenerList) {

            /** @var AbstractListener[] $listenerList */
            foreach ($listenerList as $listener) {

                if ($typeName === $event::NAME) {

                    if (!class_exists($listener)) {
                        continue;
                    }

                    /** @var AbstractListener $listener */
                    $listener = new $listener();
                    $listener->create();
                    $callback = $listener->getCallback();
                    $callback->execute($event);

                }

            }

        }

        return true;

    }

}