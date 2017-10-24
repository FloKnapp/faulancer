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