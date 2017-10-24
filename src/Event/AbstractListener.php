<?php

namespace Faulancer\Event;

/**
 * Class AbstractListener
 * @package Faulancer\Event
 * @author  Florian Knapp <office@florianknapp.de>
 */
abstract class AbstractListener
{

    /** @var Callback */
    protected $callback;

    /** @var AbstractEventType */
    protected $event;

    /**
     * AbstractListener constructor.
     * @param AbstractEventType $event
     */
    public function __construct(AbstractEventType $event) {
        $this->event = $event;
    }

    /**
     * @return void
     */
    abstract public function create();

    /**
     * @param Callback $callback
     */
    public function setCallback(Callback $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @return Callback
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param AbstractEventType $event
     */
    public function setEvent(AbstractEventType $event)
    {
        $this->event = $event;
    }

    /**
     * @return AbstractEventType
     */
    public function getEvent()
    {
        return $this->event;
    }

}