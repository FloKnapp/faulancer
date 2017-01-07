<?php

namespace Faulancer\Test\Unit;

use Faulancer\Controller\Dispatcher;
use Faulancer\Exception\DispatchFailureException;
use Faulancer\Http\Request;
use Faulancer\Http\Response;
use Faulancer\Reflection\ClassParser;
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
     *
     * @runInSeparateProcess
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
     *
     * @runInSeparateProcess
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
     *
     * @runInSeparateProcess
     */
    public function testReturnResponse()
    {
        $request = new Request();
        $request->setUri('/stub/dynamic');
        $request->setMethod('GET');

        $this->assertSame($request->getUri(), '/stub/dynamic');

        $dispatcher = new Dispatcher($request, $this->config, false);

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

        $dispatcher = new Dispatcher($request, $this->config, false);

        try {
            $dispatcher->run()->getContent();
        } catch (DispatchFailureException $e) {
            $this->assertInstanceOf(DispatchFailureException::class, $e);
        }
    }

    /**
     * @throws DispatchFailureException
     * @throws \Faulancer\Exception\ConfigInvalidException
     *
     * @runInSeparateProcess
     */
    public function testCacheDirCreate()
    {
        /** @var Config $config */
        $config = ServiceLocator::instance()->get(Config::class);

        $request = new Request();
        $request->setUri('/stub');
        $request->setMethod('GET');

        $this->assertSame($request->getUri(), '/stub');

        if (file_exists($config->get('routeCacheFile'))) {
            unlink($config->get('routeCacheFile'));
        }

        if (is_dir($config->get('projectRoot') . '/cache')) {
            rmdir($config->get('projectRoot') . '/cache');
        }

        $dispatcher = new Dispatcher($request, $this->config, true);

        $this->assertSame(1, $dispatcher->run()->getContent());
        $this->assertDirectoryExists($config->get('projectRoot') . '/cache');
        $this->assertFileExists($config->get('routeCacheFile'));

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

        $dispatcher = new Dispatcher($request, $this->config);
        $dispatcher->invalidateCache();

        $this->assertFileNotExists($this->config->get('routeCacheFile'));

        $response = $dispatcher->run();

        $this->assertFileExists($this->config->get('routeCacheFile'));
        $this->assertFileIsWritable($this->config->get('routeCacheFile'));
        $this->assertFileIsReadable($this->config->get('routeCacheFile'));

        $this->assertInstanceOf(Response::class, $response);

        $fileContents = file_get_contents($this->config->get('routeCacheFile'));

        $this->assertNotEmpty($fileContents);
        $this->assertJson($fileContents);

        $this->assertJsonStringEqualsJsonFile(
            $this->config->get('routeCacheFile'),
            json_encode($expectedContent, JSON_PRETTY_PRINT)
        );

        $request = new Request();
        $request->setUri('/stub/test');
        $request->setMethod('GET');

        $dispatcher = new Dispatcher($request, $this->config);
        $response = $dispatcher->run();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue(is_int($response->getContent()));
        $this->assertSame(200, $response->getCode());

        $dispatcher = new Dispatcher($request, $this->config);
        $response = $dispatcher->run();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue(is_int($response->getContent()));
        $this->assertSame(200, $response->getCode());

        $dispatcher->invalidateCache();

        $this->assertFalse($dispatcher->invalidateCache());
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