<?php

namespace Faulancer\Test\Unit;

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

    /**
     * @runInSeparateProcess enabled
     * 
     * @throws DispatchFailureException
     */
    public function testRouteCache()
    {
        $request = new Request();
        $request->setUri('/stub');
        $request->setMethod('GET');

        $expectedContent = [
            '/stub' => [
                'class' => "\\Faulancer\\Fixture\\Controller\\DummyController",
                'action' => 'stubStaticAction',
                'name' => 'StubStaticRoute',
                'method' => 'get'
            ]
        ];

        $expectedContent = str_replace(["\n", "\t"], "", $expectedContent);

        $this->assertSame($request->getUri(), '/stub');

        $dispatcher = new Dispatcher($request);
        $dispatcher::$ROUTE_CACHE = PROJECT_ROOT . '/cache/routes.json';
        $dispatcher->invalidateCache();

        $this->assertFileNotExists($dispatcher::$ROUTE_CACHE);

        $response = $dispatcher->run();

        $this->assertFileExists($dispatcher::$ROUTE_CACHE);
        $this->assertFileIsWritable($dispatcher::$ROUTE_CACHE);
        $this->assertFileIsReadable($dispatcher::$ROUTE_CACHE);

        $this->assertInstanceOf(Response::class, $response);

        $fileContents = file_get_contents($dispatcher::$ROUTE_CACHE);

        $this->assertNotEmpty($fileContents);
        $this->assertJson($fileContents);

        $this->assertJsonStringEqualsJsonFile(
            $dispatcher::$ROUTE_CACHE,
            json_encode($expectedContent, JSON_PRETTY_PRINT)
        );

        $dispatcher = new Dispatcher($request);
        $dispatcher::$ROUTE_CACHE = PROJECT_ROOT . '/cache/routes.json';
        $response = $dispatcher->run();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue(is_int($response->getContent()));
        $this->assertSame(200, $response->getCode());

        $dispatcher->invalidateCache();

    }

    public function testInvalidMethod()
    {
        $this->expectException(DispatchFailureException::class);

        $request = new Request();
        $request->setUri('/stub');
        $request->setMethod('POST');

        $dispatcher = new Dispatcher($request, false);
        $dispatcher->run();
    }

}