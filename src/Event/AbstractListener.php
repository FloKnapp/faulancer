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

    /**
     * @return mixed
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

}