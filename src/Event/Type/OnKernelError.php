<?php

namespace Faulancer\Event\Type;

use Faulancer\Event\AbstractEvent;

/**
 * Class OnKernelError
 * @package Faulancer\Event\Type
 * @author  Florian Knapp <office@florianknapp.de>
 */
class OnKernelError extends AbstractEvent
{

    const NAME = 'kernel.error';

}