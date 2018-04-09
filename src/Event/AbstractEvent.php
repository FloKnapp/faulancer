<?php

namespace Faulancer\Event;

/**
 * Class AbstractEvent
 * @package Faulancer\Event
 * @author  Florian Knapp <office@florianknapp.de>
 */
abstract class AbstractEvent
{

    const NAME = '';

    /** @var \stdClass */
    protected $currentInstance;

    /**
     * AbstractEventType constructor.
     *
     * @param $currentInstance
     */
    public function __construct($currentInstance)
    {
        $this->currentInstance = $currentInstance;
    }

    /**
     * @return \stdClass
     */
    public function getCurrentInstance()
    {
        return $this->currentInstance;
    }

}