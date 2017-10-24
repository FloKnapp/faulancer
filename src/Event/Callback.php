<?php

namespace Faulancer\Event;

/**
 * Class AbstractCallback
 * @package Faulancer\Event
 * @author  Florian Knapp <office@florianknapp.de>
 */
class Callback
{

    /** @var \Closure */
    protected $callback;

    /**
     * @param \Closure $callback
     */
    public function set(\Closure $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param AbstractEventType $event
     *
     * @return \Closure
     */
    public function execute($event)
    {
        return call_user_func($this->callback, $event);
    }

}