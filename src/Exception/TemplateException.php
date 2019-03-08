<?php

namespace Faulancer\Exception;

use Throwable;

/**
 * Class TemplateException
 *
 * @package Faulancer\Exception
 * @author  Florian Knapp <office@florianknapp.de>
 */
class TemplateException extends Exception
{

    public function __construct($message = "", $code = 0, $file = '', $line = 0, Throwable $previous = null)
    {
        $this->file = $file;
        $this->line = $line;
        parent::__construct($message, $code, $previous);
    }

}