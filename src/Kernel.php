<?php

namespace Faulancer;

use Faulancer\Controller\Dispatcher;
use Faulancer\Controller\ErrorController;
use Faulancer\Exception\DispatchFailureException;
use Faulancer\Http\Request;

/**
 * File Kernel.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class Kernel
{

    /** @var Request */
    protected $request;

    /** @var boolean */
    protected $routeCacheEnabled;

    public function __construct(Request $request, $routeCacheEnabled = true)
    {
        $this->request = $request;
        $this->routeCacheEnabled = $routeCacheEnabled;
    }

    public function run()
    {
        $dispatcher = new Dispatcher($this->request);

        try {
            $response = $dispatcher->run();
            return $response->getContent();
        } catch (DispatchFailureException $e) {
            return ErrorController::notFoundAction();
        }

    }

}