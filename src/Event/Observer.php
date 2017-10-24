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

    /**
     * Observer constructor (private).
     */
    private function __construct() {}

    /**
     * @return self
     */
    public static function instance()
    {
        if (!self::$instance) {

            /** @var Config $config */
            $config = ServiceLocator::instance()->get(Config::class);
            self::$listener = $config->get('eventListener');
            self::$instance = new self();

        }

        return self::$instance;
    }

    /**
     * @param AbstractEventType $eventType
     */
    public function trigger(AbstractEventType $eventType)
    {
        foreach (self::$listener as $typeName => $listenerList) {

            /** @var AbstractListener[] $listenerList */
            foreach ($listenerList as $listener) {

                if ($typeName === $eventType::EVENT_TYPE) {

                    /** @var AbstractListener $listener */
                    $listener = new $listener($eventType);
                    $listener->create()->getCallback()->execute($eventType);

                }

            }



        }

    }

}