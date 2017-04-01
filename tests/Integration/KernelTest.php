<?php

namespace Faulancer\Test\Integration;

use Faulancer\Http\Request;
use Faulancer\Kernel;
use Faulancer\Service\Config;
use Faulancer\Service\ResponseService;
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
        $request->setUri('/test');

        new Kernel($request, $config, false);

        $this->assertNotEmpty($config->get('applicationRoot'));
        $this->assertNotEmpty($config->get('projectRoot'));
        $this->assertNotEmpty($config->get('viewsRoot'));
    }

    public function testFailureDispatch()
    {
        $this->expectException(\TypeError::class);

        $request = new Request();
        $request->setMethod('GET');
        $request->setUri('/test');

        $config = [];

        $kernel = new Kernel($request, $config, false);
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
        $responseMock = $this->createPartialMock(ResponseService::class, ['setResponseHeader']);
        $responseMock->method('setResponseHeader')->will($this->returnValue(true));

        ServiceLocator::instance()->set('Faulancer\Service\ResponseService', $responseMock);

        $request = new Request();
        $request->setMethod('GET');
        $request->setUri('/stub');

        $kernel = new Kernel($request, $config, false);
        $response = $kernel->run();

        $this->assertNotEmpty($response);
        $this->assertSame('1', $response);
    }

}