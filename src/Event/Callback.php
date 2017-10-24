<?php

namespace Faulancer\Event;

/**
 * Class AbstractCallback
 * @package Faulancer\Event
 * @author  Florian Knapp <office@florianknapp.de>
 */
class Callback
{

    /** @var mixed */
    protected $callback;

    /**
     * Callback constructor.
     *
     * @param mixed|null $callback
     */
    public function __construct($callback = null)
    {
        $this->callback = $callback;
    }

    /**
     * @param $callback
     *
     * @return void
     */
    public function set($callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param AbstractEventType $event
     *
     * @return mixed
     */
    public function execute(AbstractEventType $event)
    {
        return call_user_func($this->callback, $event);
    }

}