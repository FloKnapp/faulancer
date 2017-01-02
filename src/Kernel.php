<?php

namespace Faulancer;

use Faulancer\Controller\Dispatcher;
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
        return Dispatcher::run($this->request);
    }

}