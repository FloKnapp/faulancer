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

        if (!empty($this->config)) {
            define('APPLICATION_ROOT', $this->config['applicationRoot']);
            define('PROJECT_ROOT',     $this->config['projectRoot']);
            define('VIEWS_ROOT',       $this->config['viewsRoot']);
            define('NAMESPACE_PREFIX', $this->config['namespacePrefix']);
        }

        $dispatcher = new Dispatcher($this->request, $this->routeCacheEnabled);

        try {
            echo $dispatcher->run()->getContent();
            return true;
        } catch (DispatchFailureException $e) {
            return ErrorController::notFoundAction()->getContent();
        }

    }

}