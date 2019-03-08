<?php
/**
 * Class InvalidFormElementException | InvalidFormElementException.php
 * @package Faulancer\Exception
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Exception;

use Throwable;

/**
 * Class InvalidFormElementException
 */
class InvalidFormElementException extends Exception {

    public function __construct($message = "", $code = 0, $line = 0, $file = '', Throwable $previous = null)
    {
        $this->line = $line;
        $this->file = $file;
        parent::__construct($message, $code, $previous);
    }

}