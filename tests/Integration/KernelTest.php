<?php

namespace Faulancer\Test\Integration;

use Faulancer\Http\Request;
use Faulancer\Kernel;
use Faulancer\Service\Config;
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

        $conf = [
            'applicationRoot' => 'test',
            'projectRoot'     => 'test',
            'viewsRoot'       => 'test'
        ];

        new Kernel($request, $conf, false);

        $this->assertNotEmpty($config->get('applicationRoot'));
        $this->assertNotEmpty($config->get('projectRoot'));
        $this->assertNotEmpty($config->get('viewsRoot'));

    }

    /**
     * @runInSeparateProcess
     */
    public function testFailure()
    {
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
    public function testSuccess()
    {
        $request = new Request();
        $request->setMethod('GET');
        $request->setUri('/stub');

        $config = [];

        $kernel = new Kernel($request, $config, false);
        $response = $kernel->run();

        $this->assertNotEmpty($response);
        $this->assertSame(1, $response);
    }

}