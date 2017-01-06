<?php

namespace Faulancer\Test\Unit;

use Faulancer\Exception\InvalidArgumentException;
use Faulancer\Http\Uri;
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
        $mock = $this->createPartialMock(Uri::class, ['terminate']);
        $mock->method('terminate')->will($this->returnValue(true));

        /** @var Uri $mock */
        $result = $mock->redirect('/test', 301);

        $this->assertTrue($result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testWrongCode()
    {
        $this->expectException(InvalidArgumentException::class);
        (new Uri())->redirect('/stub', 442);
    }

}