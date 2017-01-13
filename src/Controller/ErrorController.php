<?php
/**
 * Class ErrorController | ErrorController.php
 *
 * @package Faulancer\Controller
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Controller;

use Faulancer\Http\Response;

/**
 * Class ErrorController
 */
class ErrorController
{
    /**
     * Return a 404 status code with corresponding body
     * @return Response
     */
    public static function notFoundAction()
    {
        $response = new Response();
        $response->setCode(404);
        $response->setContent('Not found');
        return $response;
    }
}