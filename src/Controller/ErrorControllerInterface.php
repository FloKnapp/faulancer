<?php

namespace Faulancer\Controller;

use Faulancer\Http\Response;

/**
 * Class ErrorControllerInterface
 *
 * @package Faulancer\Controller
 * @author  Florian Knapp <office@florianknapp.de>
 */
interface ErrorControllerInterface
{

    /**
     * @return Response
     */
    public function notFoundAction();

    /**
     * @return Response
     */
    public function notPermittedAction();

}