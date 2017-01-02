<?php

namespace tests\integration;

use Faulancer\Controller\Dispatcher;
use Faulancer\Http\Request;
use PHPUnit\Framework\TestCase;


/**
 * File DispatcherTest.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class DispatcherTest extends TestCase
{

    protected $request;

    public function setUp()
    {
        $this->request = new Request();

        if (!defined('PROJECT_ROOT')) {
            define('PROJECT_ROOT', realpath(__DIR__ . '/..'));
        }

        if (!defined('NAMESPACE_ROOT')) {
            define('NAMESPACE_ROOT', 'stubs');
        }

        require_once PROJECT_ROOT . '/stubs/Controller/DummyController.php';
    }

    /**
     * Test static routing
     */
    public function testStaticRoute()
    {
        $request = new Request();
        $request->setUri('/stub');

        $this->assertSame($request->getUri(), '/stub');

        $dispatcher = Dispatcher::run($request, false);

        $this->assertSame(1, $dispatcher);
    }

    /**
     * Test static routing
     */
    public function testDynamicRoute()
    {
        $request = new Request();
        $request->setUri('/stub/dynamic');

        $this->assertSame($request->getUri(), '/stub/dynamic');

        $dispatcher = Dispatcher::run($request, false);

        $this->assertSame(2, $dispatcher);
    }

    /**
     * Test error page
     *
     * @runInSeparateProcess
     */
    public function test404Route()
    {
        $request = new Request();
        $request->setUri('/stubs');

        $this->assertSame($request->getUri(), '/stubs');

        $dispatcher = Dispatcher::run($request, false);

        $this->assertSame(null, $dispatcher);
    }

}