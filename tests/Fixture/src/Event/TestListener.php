<?php

namespace Faulancer\Fixture\Event;

use Faulancer\Event\AbstractEvent;
use Faulancer\Event\AbstractListener;
use Faulancer\Event\Callback;

/**
 * Class TestListener
 * @author Florian Knapp <office@florianknapp.de>
 */
class TestListener extends AbstractListener
{

    public function create()
    {
        $callback = new Callback(function(AbstractEvent $event) {
            $event->getCurrentInstance();
        });
        $this->setCallback($callback);
    }

}