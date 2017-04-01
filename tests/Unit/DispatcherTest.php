<?php

namespace Faulancer\Test\Unit;

use Faulancer\Controller\Dispatcher;
use Faulancer\Exception\DispatchFailureException;
use Faulancer\Exception\IncompatibleResponse;
use Faulancer\Exception\MethodNotFoundException;
use Faulancer\Http\Request;
use Faulancer\Http\Response;
use Faulancer\Service\Config;
use Faulancer\Service\ResponseService;
use Faulancer\ServiceLocator\ServiceInterface;
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
        /** @var ServiceInterface|\PHPUnit_Framework_MockObject_MockObject $responseMock */
        $responseMock = $this->createPartialMock(ResponseService::class, ['setResponseHeader']);
        $responseMock->method('setResponseHeader')->will($this->returnValue(true));

        ServiceLocator::instance()->set('Faulancer\Service\ResponseService', $responseMock);

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

        $dispatcher = new Dispatcher($request, $this->config);
        $this->assertSame(1, $dispatcher->dispatch());
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

        $dispatcher = new Dispatcher($request, $this->config);
        $this->assertSame(2, $dispatcher->dispatch());
    }

    public function testDynamicRouteTooLong()
    {
        $this->expectException(MethodNotFoundException::class);

        $request = new Request();
        $request->setUri('/stub/dynamic/all');
        $request->setMethod('GET');

        $this->assertSame($request->getUri(), '/stub/dynamic/all');

        $dispatcher = new Dispatcher($request, $this->config);
        $dispatcher->dispatch();
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
        $this->assertSame(2, $dispatcher->dispatch());
    }

    /**
     * Test error page
     */
    public function test404Route()
    {
        $this->expectException(MethodNotFoundException::class);

        $request = new Request();
        $request->setUri('/stubs');
        $request->setMethod('GET');

        $this->assertSame($request->getUri(), '/stubs');

        $dispatcher = new Dispatcher($request, $this->config);
        $dispatcher->dispatch()->getContent();
    }

    public function testInvalidRequestMethod()
    {
        $this->expectException(MethodNotFoundException::class);

        $request = new Request();
        $request->setUri('/stub');
        $request->setMethod('POST');

        $dispatcher = new Dispatcher($request, $this->config);
        $dispatcher->dispatch();
    }

    public function testNoValidResponse()
    {
        $this->expectException(IncompatibleResponse::class);

        $request = new Request();
        $request->setUri('/stub-no-response');
        $request->setMethod('GET');

        $dispatcher = new Dispatcher($request, $this->config);
        $dispatcher->dispatch();
    }

    public function testCustomAssetsPath()
    {
        $request = new Request();
        $request->setUri('/core/assets/css/main.css');
        $request->setMethod('GET');

        $this->assertSame($request->getUri(), '/core/assets/css/main.css');

        $dispatcherMock = $this->getMockBuilder(Dispatcher::class)
            ->setMethods(['sendCssFileHeader'])
            ->setConstructorArgs([$request, $this->config])
            ->getMock();

        $dispatcherMock
            ->expects($this->any())
            ->method('sendCssFileHeader')
            ->will($this->returnValue(true));

        $this->assertTrue($dispatcherMock->dispatch());
    }

    public function testCustomJsPath()
    {
        $request = new Request();
        $request->setUri('/core/assets/js/engine.js');
        $request->setMethod('GET');

        $this->assertSame($request->getUri(), '/core/assets/js/engine.js');

        $dispatcherMock = $this->getMockBuilder(Dispatcher::class)
            ->setMethods(['sendJsFileHeader'])
            ->setConstructorArgs([$request, $this->config])
            ->getMock();

        $dispatcherMock
            ->expects($this->any())
            ->method('sendJsFileHeader')
            ->will($this->returnValue(true));

        $this->assertTrue($dispatcherMock->dispatch());
    }

}