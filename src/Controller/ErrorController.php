<?php
/**
 * Class ErrorController | ErrorController.php
 *
 * @package Faulancer\Controller
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Controller;

use Faulancer\Exception\Exception;

/**
 * Class ErrorController
 */
class ErrorController
{

    /**
     * ErrorController constructor.
     * @param Exception $e
     */
    public function __construct(Exception $e)
    {
        $this->exception = $e;
    }

    public function displayError()
    {
        var_dump($_ENV);

        return true;
    }
}