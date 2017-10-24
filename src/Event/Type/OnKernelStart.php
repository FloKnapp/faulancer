<?php

namespace Faulancer\Event\Type;

use Faulancer\Event\AbstractEventType;

/**
 * Class OnKernelStart
 * @package Faulancer\Event\Type
 * @author  Florian Knapp <office@florianknapp.de>
 */
class OnKernelStart extends AbstractEventType
{

    const EVENT_TYPE = 'on_kernel_start';

}