<?php

namespace Faulancer\Event\Type;

use Faulancer\Event\AbstractEvent;

/**
 * Class OnKernelErrorException
 * @package Faulancer\Event\Type
 * @author  Florian Knapp <office@florianknapp.de>
 */
class OnKernelErrorException extends AbstractEvent
{

    const NAME = 'kernel.error_exception';

}