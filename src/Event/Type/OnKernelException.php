<?php

namespace Faulancer\Event\Type;

use Faulancer\Event\AbstractEventType;

/**
 * Class OnKernelException
 * @package Faulancer\Event\Type
 * @author  Florian Knapp <office@florianknapp.de>
 */
class OnKernelException extends AbstractEventType
{

    const EVENT_TYPE = 'on_kernel_exception';

}