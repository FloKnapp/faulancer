<?php

namespace Faulancer\Test\Integration;

use Faulancer\Http\Request;
use Faulancer\Http\Response;
use Faulancer\Kernel;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceInterface;
use Faulancer\ServiceLocator\ServiceLocator;
use PHPUnit\Framework\TestCase;

/**
 * File KernelTest.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class KernelTest extends TestCase
{

    public function testConfigSet()
    {
        /** @var Config $config */
        $config = ServiceLocator::instance()->get(Config::class);

        $request = new Request();
        $request->setPath('/test');

        new Kernel($request, $config);

        $this->assertNotEmpty($config->get('applicationRoot'));
        $this->assertNotEmpty($config->get('projectRoot'));
        $this->assertNotEmpty($config->get('viewsRoot'));
    }

    public function testFailureDispatch()
    {
        $this->expectException(\TypeError::class);

        $request = new Request();
        $request->setMethod('GET');
        $request->setPath('/test');

        $config = [];

        $kernel = new Kernel($request, $config);
        $response = $kernel->run();

        $this->assertNotEmpty($response);
        $this->assertTrue(is_string($response));
        $this->assertSame('Not found', $response);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSuccessDispatch()
    {
        /** @var Config $config */
        $config = ServiceLocator::instance()->get(Config::class);

        /** @var ServiceInterface|\PHPUnit_Framework_MockObject_MockObject $responseMock */
        $responseMock = $this->createPartialMock(Response::class, ['setResponseHeader']);
        $responseMock->method('setResponseHeader')->will($this->returnValue(true));

        ServiceLocator::instance()->set('Faulancer\Http\Response', $responseMock);

        $request = new Request();
        $request->setMethod('GET');
        $request->setPath('/stub');

        $kernel = new Kernel($request, $config);
        $response = $kernel->run();

        $this->assertNotEmpty($response);
        $this->assertSame(1, $response);
    }

}