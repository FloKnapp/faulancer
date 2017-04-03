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
        unset($_POST);

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

        self::assertSame($request->getUri(), '/stub');

        $dispatcher = new Dispatcher($request, $this->config);
        self::assertInstanceOf(Response::class, $dispatcher->dispatch());
        self::assertSame(1, $dispatcher->dispatch()->getContent());
    }

    /**
     * Test static routing
     */
    public function testDynamicRoute()
    {
        $request = new Request();
        $request->setUri('/stub/dynamic');
        $request->setMethod('GET');

        self::assertSame($request->getUri(), '/stub/dynamic');

        $dispatcher = new Dispatcher($request, $this->config);
        self::assertSame(2, $dispatcher->dispatch()->getContent());
    }

    public function testDynamicRouteTooLong()
    {
        $this->expectException(MethodNotFoundException::class);

        $request = new Request();
        $request->setUri('/stub/dynamic/all');
        $request->setMethod('GET');

        self::assertSame($request->getUri(), '/stub/dynamic/all');

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

        self::assertSame($request->getUri(), '/stub/dynamic');

        $dispatcher = new Dispatcher($request, $this->config);
        self::assertSame(2, $dispatcher->dispatch()->getContent());
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

        self::assertSame($request->getUri(), '/stubs');

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

        self::assertSame($request->getUri(), '/core/assets/css/main.css');

        /** @var Dispatcher|\PHPUnit_Framework_MockObject_MockObject $dispatcherMock */
        $dispatcherMock = $this->getMockBuilder(Dispatcher::class)
            ->setMethods(['sendCssFileHeader'])
            ->setConstructorArgs([$request, $this->config])
            ->getMock();

        $dispatcherMock
            ->expects($this->any())
            ->method('sendCssFileHeader')
            ->will($this->returnValue(true));

        self::assertTrue($dispatcherMock->dispatch());
    }

    public function testCustomJsPath()
    {
        $request = new Request();
        $request->setUri('/core/assets/js/engine.js');
        $request->setMethod('GET');

        self::assertSame($request->getUri(), '/core/assets/js/engine.js');

        /** @var Dispatcher|\PHPUnit_Framework_MockObject_MockObject $dispatcherMock */
        $dispatcherMock = $this->getMockBuilder(Dispatcher::class)
            ->setMethods(['sendJsFileHeader'])
            ->setConstructorArgs([$request, $this->config])
            ->getMock();

        $dispatcherMock
            ->expects($this->any())
            ->method('sendJsFileHeader')
            ->will($this->returnValue(true));

        self::assertTrue($dispatcherMock->dispatch());
    }

    public function testApiRequest()
    {
        $request = new Request();
        $request->setUri('/api/v1/test');
        $request->setMethod('GET');

        self::assertSame($request->getUri(), '/api/v1/test');

        /** @var Dispatcher|\PHPUnit_Framework_MockObject_MockObject $dispatcherMock */
        $dispatcherMock = $this->getMockBuilder(Dispatcher::class)
            ->setMethods(['sendJsFileHeader'])
            ->setConstructorArgs([$request, $this->config])
            ->getMock();

        $dispatcherMock
            ->expects($this->any())
            ->method('sendJsFileHeader')
            ->will($this->returnValue(true));

        $response = $dispatcherMock->dispatch();

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('{"param":false}', $response->getContent());
    }

    public function testDynamicApiRequest()
    {
        $request = new Request();
        $request->setUri('/api/v1/test/word');
        $request->setMethod('GET');

        self::assertSame($request->getUri(), '/api/v1/test/word');

        $dispatcher = new Dispatcher($request, $this->config);
        $response   = $dispatcher->dispatch();

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame('{"param":"word"}', $response->getContent());
    }

    public function testDynamicApiRequestTooLong()
    {
        $this->expectException(MethodNotFoundException::class);

        $request = new Request();
        $request->setUri('/api/v1/test/word/not-covered');
        $request->setMethod('GET');

        self::assertSame($request->getUri(), '/api/v1/test/word/not-covered');

        $dispatcher = new Dispatcher($request, $this->config);

        $dispatcher->dispatch();
    }

    public function testApiPostRequest()
    {
        $request = new Request();
        $request->setUri('/api/v1/test/word');
        $request->setMethod('POST');

        self::assertSame($request->getUri(), '/api/v1/test/word');

        $dispatcher = new Dispatcher($request, $this->config);
        $response   = $dispatcher->dispatch();

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame('"word"', $response->getContent());
    }

    public function testApiUpdateRequest()
    {
        $request = new Request();
        $request->setUri('/api/v1/test/word');
        $request->setMethod('UPDATE');

        self::assertSame($request->getUri(), '/api/v1/test/word');

        $dispatcher = new Dispatcher($request, $this->config);
        $response   = $dispatcher->dispatch();

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame('"word"', $response->getContent());
    }

    public function testApiDeleteRequest()
    {
        $request = new Request();
        $request->setUri('/api/v1/test/word');
        $request->setMethod('DELETE');

        self::assertSame($request->getUri(), '/api/v1/test/word');

        $dispatcher = new Dispatcher($request, $this->config);
        $response   = $dispatcher->dispatch();

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame('"word"', $response->getContent());
    }

    public function testApiWithQueryParam()
    {
        $request = new Request();
        $request->setUri('/api/v1/test?test=yolo');
        $request->setMethod('');

        self::assertSame($request->getUri(), '/api/v1/test');

        $dispatcher = new Dispatcher($request, $this->config);
        $response   = $dispatcher->dispatch();

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame('{"param":"yolo"}', $response->getContent());
    }

    /**
     * Expect regular GET call without method
     */
    public function testApiNoMethod()
    {
        $request = new Request();
        $request->setUri('/api/v1/test/word');
        $request->setMethod('');

        self::assertSame($request->getUri(), '/api/v1/test/word');

        $dispatcher = new Dispatcher($request, $this->config);
        $response   = $dispatcher->dispatch();

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame('{"param":"word"}', $response->getContent());
    }

    public function testMethodDoesntExist()
    {
        $this->expectException(MethodNotFoundException::class);

        $request = new Request();
        $request->setUri('/stub-no-method');
        $request->setMethod('GET');

        self::assertSame($request->getUri(), '/stub-no-method');

        $dispatcher = new Dispatcher($request, $this->config);
        $response   = $dispatcher->dispatch();
    }

}