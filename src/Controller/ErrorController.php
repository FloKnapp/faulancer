<?php

namespace Faulancer\Controller;

use Faulancer\Http\Response;

/**
 * File ErrorController.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class ErrorController
{
    public static function notFoundAction()
    {
        $response = new Response();
        $response->setCode(404);
        $response->setContent('Not found');
        return $response;
    }
}