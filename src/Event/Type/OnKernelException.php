<?php

namespace Faulancer\Event\Type;

use Faulancer\Event\AbstractEvent;

/**
 * Class OnKernelException
 * @package Faulancer\Event\Type
 * @author  Florian Knapp <office@florianknapp.de>
 */
class OnKernelException extends AbstractEvent
{

    const NAME = 'kernel.exception';

}