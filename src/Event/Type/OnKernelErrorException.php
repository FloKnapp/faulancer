<?php

namespace Faulancer\Event\Type;

use Faulancer\Event\AbstractEventType;

/**
 * Class OnKernelErrorException
 * @package Faulancer\Event\Type
 * @author  Florian Knapp <office@florianknapp.de>
 */
class OnKernelErrorException extends AbstractEventType
{

    const EVENT_TYPE = 'on_kernel_error_exception';

}