<?php

namespace Faulancer\Event\Type;

use Faulancer\Event\AbstractEventType;

/**
 * Class OnKernelError
 * @package Faulancer\Event\Type
 * @author  Florian Knapp <office@florianknapp.de>
 */
class OnKernelError extends AbstractEventType
{

    const EVENT_TYPE = 'on_kernel_error';

}