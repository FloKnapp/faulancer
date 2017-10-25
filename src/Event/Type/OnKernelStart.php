<?php

namespace Faulancer\Event\Type;

use Faulancer\Event\AbstractEvent;

/**
 * Class OnKernelStart
 * @package Faulancer\Event\Type
 * @author  Florian Knapp <office@florianknapp.de>
 */
class OnKernelStart extends AbstractEvent
{

    const NAME = 'kernel.start';

}