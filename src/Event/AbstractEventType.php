<?php

namespace Faulancer\Event;

/**
 * Class AbstractEventType
 * @package Faulancer\Event
 * @author  Florian Knapp <office@florianknapp.de>
 */
abstract class AbstractEventType
{

    const EVENT_TYPE = '';

    /** @var \Object */
    protected $instance;

    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    public function getInstance()
    {
        return $this->instance;
    }

}