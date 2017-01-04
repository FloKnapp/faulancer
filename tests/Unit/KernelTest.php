<?php

namespace Faulancer\Test\Unit;

use Faulancer\Http\Request;
use Faulancer\Kernel;
use PHPUnit\Framework\TestCase;

/**
 * File KernelTest.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class KernelTest extends TestCase
{

    /**
     * @runInSeparateProcess
     */
    public function testFailure()
    {
        $request = new Request();
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

        $this->expectOutputString('1');
        $this->assertNotEmpty($response);
        $this->assertSame(true, $response);
    }

}