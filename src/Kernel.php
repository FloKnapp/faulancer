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

    public function __construct(Request $request, array $config, $routeCacheEnabled = true)
    {
        $this->request = $request;
        $this->config  = $config;
        $this->routeCacheEnabled = $routeCacheEnabled;
    }

    public function run()
    {

        define('APPLICATION_ROOT', $this->config['applicationRoot']);
        define('PROJECT_ROOT',     $this->config['projectRoot']);
        define('VIEWS_ROOT',       $this->config['viewsRoot']);

        $dispatcher = new Dispatcher($this->request);

        try {
            echo $dispatcher->run()->getContent();
        } catch (DispatchFailureException $e) {
            return ErrorController::notFoundAction();
        }

    }

}