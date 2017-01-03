<?php

namespace tests\integration;

use Faulancer\Controller\Dispatcher;
use Faulancer\Exception\DispatchFailureException;
use Faulancer\Http\Request;
use Faulancer\Http\Response;
use PHPUnit\Framework\TestCase;

/**
 * File DispatcherTest.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class DispatcherTest extends TestCase
{

    public function setUp()
    {
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
     *
     * @runInSeparateProcess
     */
    public function testStaticRoute()
    {
        $request = new Request();
        $request->setUri('/stub');
        $request->setMethod('GET');

        $this->assertSame($request->getUri(), '/stub');

        $dispatcher = new Dispatcher($request, false);

        $this->assertSame(1, $dispatcher->run()->getContent());
    }

    /**
     * Test static routing
     *
     * @runInSeparateProcess
     */
    public function testDynamicRoute()
    {
        $request = new Request();
        $request->setUri('/stub/dynamic');
        $request->setMethod('GET');

        $this->assertSame($request->getUri(), '/stub/dynamic');

        $dispatcher = new Dispatcher($request, false);

        $this->assertSame(2, $dispatcher->run()->getContent());
    }

    /**
     * Test if dispatcher returns a response object
     *
     * @runInSeparateProcess
     */
    public function testReturnResponse()
    {
        $request = new Request();
        $request->setUri('/stub/dynamic');
        $request->setMethod('GET');

        $this->assertSame($request->getUri(), '/stub/dynamic');

        $dispatcher = new Dispatcher($request, false);

        $this->assertInstanceOf(Response::class, $dispatcher->run());
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
        $request->setMethod('GET');

        $this->assertSame($request->getUri(), '/stubs');

        $dispatcher = new Dispatcher($request, false);

        try {
            $dispatcher->run()->getContent();
        } catch (DispatchFailureException $e) {
            $this->assertInstanceOf(DispatchFailureException::class, $e);
        }
    }

}