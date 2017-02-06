<?php

namespace Faulancer\Test\Unit;

use Faulancer\Exception\InvalidArgumentException;
use Faulancer\Http\Http;
use PHPUnit\Framework\TestCase;

/**
 * File HttpUriTest.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class HttpUriTest extends TestCase
{

    /**
     * @runInSeparateProcess
     */
    public function testRedirect()
    {
        $mock = $this->createPartialMock(Http::class, ['triggerRedirect']);
        $mock->method('triggerRedirect')->will($this->returnValue(true));

        /** @var Http $mock */
        $result = $mock->redirect('/test', 301);

        $this->assertTrue($result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testWrongCode()
    {
        $this->expectException(InvalidArgumentException::class);
        (new Http())->redirect('/stub', 442);
    }

}