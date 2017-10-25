<?php

namespace Faulancer\Event;

/**
 * Class AbstractCallback
 * @package Faulancer\Event
 * @author  Florian Knapp <office@florianknapp.de>
 */
class Callback
{

    /** @var callable */
    protected $callback;

    /**
     * Callback constructor.
     *
     * @param callable|null $callback
     */
    public function __construct(callable $callback = null)
    {
        $this->callback = $callback;
    }

    /**
     * @param AbstractEvent $event
     *
     * @return mixed
     * @codeCoverageIgnore
     */
    public function execute(AbstractEvent $event)
    {
        try {
            return call_user_func($this->callback, $event);
        } catch (\Exception $e) {
            return null;
        }
    }

}