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

    public function testGetUri()
    {
        $_SERVER['REQUEST_URI'] = '/stub';
        $uri = Uri::getUri();
        $this->assertTrue(is_string($uri));
        $this->assertSame('/stub', $uri);
    }

    /**
     * @runInSeparateProcess
     */
    public function testRedirect()
    {
        $this->assertFalse(Uri::redirect('/test'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testWrongCode()
    {
        $this->expectException(InvalidArgumentException::class);
        Uri::redirect('/stub', 442);
    }

}