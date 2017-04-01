<?php

namespace Faulancer\Test\Unit;

use Faulancer\Controller\Dispatcher;
use Faulancer\Exception\IncompatibleResponseException;
use Faulancer\Exception\MethodNotFoundException;
use Faulancer\Http\JsonResponse;
use Faulancer\Http\Request;
use Faulancer\Http\Response;
use Faulancer\Service\Config;
use Faulancer\Service\JsonResponseService;
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

        /** @var ServiceInterface|\PHPUnit_Framework_MockObject_MockObject $jsonResponseMock */
        $jsonResponseMock = $this->createPartialMock(JsonResponseService::class, ['setResponseHeader']);
        $jsonResponseMock->method('setResponseHeader')->will($this->returnValue(true));

        ServiceLocator::instance()->set('Faulancer\Service\ResponseService', $responseMock);
        ServiceLocator::instance()->set('Faulancer\Service\JsonResponseService', $jsonResponseMock);

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
        $this->assertInstanceOf(Response::class, $dispatcher->dispatch());
        $this->assertSame(1, $dispatcher->dispatch()->getContent());
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
        $this->assertSame(2, $dispatcher->dispatch()->getContent());
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
        $this->assertSame(2, $dispatcher->dispatch()->getContent());
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
        $dispatcher->dispatch();
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
        $this->expectException(IncompatibleResponseException::class);

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

        /** @var Dispatcher|\PHPUnit_Framework_MockObject_MockObject $dispatcherMock */
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

        /** @var Dispatcher|\PHPUnit_Framework_MockObject_MockObject $dispatcherMock */
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

    public function testApiRequest()
    {
        $request = new Request();
        $request->setUri('/api/v1/test');
        $request->setMethod('GET');

        $this->assertSame($request->getUri(), '/api/v1/test');

        /** @var Dispatcher|\PHPUnit_Framework_MockObject_MockObject $dispatcherMock */
        $dispatcherMock = $this->getMockBuilder(Dispatcher::class)
            ->setMethods(['sendJsFileHeader'])
            ->setConstructorArgs([$request, $this->config])
            ->getMock();

        $dispatcherMock
            ->expects($this->any())
            ->method('sendJsFileHeader')
            ->will($this->returnValue(true));

        $this->assertInstanceOf(Response::class, $dispatcherMock->dispatch());
    }

    public function testDynamicApiRequest()
    {
        $request = new Request();
        $request->setUri('/api/v1/test/word');
        $request->setMethod('GET');

        $this->assertSame($request->getUri(), '/api/v1/test/word');

        $dispatcher = new Dispatcher($request, $this->config);

        $response = $dispatcher->dispatch();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame('{"param":"word"}', $response->getContent());
    }

    public function testDynamicApiRequestTooLong()
    {
        $this->expectException(MethodNotFoundException::class);

        $request = new Request();
        $request->setUri('/api/v1/test/word/not-covered');
        $request->setMethod('GET');

        $this->assertSame($request->getUri(), '/api/v1/test/word/not-covered');

        $dispatcher = new Dispatcher($request, $this->config);

        $dispatcher->dispatch();
    }

    public function testApiPostRequest()
    {
        $request = new Request();
        $request->setUri('/api/v1/test/word');
        $request->setMethod('POST');

        $this->assertSame($request->getUri(), '/api/v1/test/word');

        $dispatcher = new Dispatcher($request, $this->config);

        $response = $dispatcher->dispatch();

        $this->assertSame('');
    }

}