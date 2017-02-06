<?php

namespace Faulancer\Test\Unit;

use Faulancer\Controller\Dispatcher;
use Faulancer\Exception\DispatchFailureException;
use Faulancer\Http\Request;
use Faulancer\Http\Response;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceLocator;
use PHPUnit\Framework\TestCase;

/**
 * File DispatcherTest.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class DispatcherTest extends TestCase
{

    /** @var Config */
    protected $config;

    public function setUp()
    {
        /** @var Config $config */
        $this->config = ServiceLocator::instance()->get(Config::class);
    }

    /**
     * Test static routing
     */
    public function testStaticRoute()
    {
        $request = new Request();
        $request->setUri('/stub');
        $request->setMethod('GET');

        $this->assertSame($request->getUri(), '/stub');

        $dispatcher = new Dispatcher($request, $this->config, false);

        $this->assertSame(1, $dispatcher->run()->getContent());
    }

    /**
     * Test static routing
     */
    public function testDynamicRoute()
    {
        $request = new Request();
        $request->setUri('/stub/dynamic');
        $request->setMethod('GET');

        $this->assertSame($request->getUri(), '/stub/dynamic');

        $dispatcher = new Dispatcher($request, $this->config, false);

        $this->assertSame(2, $dispatcher->run()->getContent());
    }

    public function testDynamicRouteTooLong()
    {
        $this->expectException(DispatchFailureException::class);

        $request = new Request();
        $request->setUri('/stub/dynamic/all');
        $request->setMethod('GET');

        $this->assertSame($request->getUri(), '/stub/dynamic/all');

        $dispatcher = new Dispatcher($request, $this->config, false);

        $this->assertSame(2, $dispatcher->run()->getContent());
    }

    /**
     * Test if dispatcher returns a response object
     */
    public function testReturnResponse()
    {
        $request = new Request();
        $request->setUri('/stub/dynamic');
        $request->setMethod('GET');

        $this->assertSame($request->getUri(), '/stub/dynamic');

        $dispatcher = new Dispatcher($request, $this->config);

        $this->assertInstanceOf(Response::class, $dispatcher->run());
    }

    /**
     * Test error page
     */
    public function test404Route()
    {
        $request = new Request();
        $request->setUri('/stubs');
        $request->setMethod('GET');

        $this->assertSame($request->getUri(), '/stubs');

        $dispatcher = new Dispatcher($request, $this->config, false);

        try {
            $dispatcher->run()->getContent();
        } catch (DispatchFailureException $e) {
            $this->assertInstanceOf(DispatchFailureException::class, $e);
        }
    }

    public function testInvalidRequestMethod()
    {
        $this->expectException(DispatchFailureException::class);

        $request = new Request();
        $request->setUri('/stub');
        $request->setMethod('POST');

        $dispatcher = new Dispatcher($request, $this->config, false);
        $dispatcher->run();
    }

}