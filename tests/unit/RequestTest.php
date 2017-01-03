<?php

namespace tests\unit;

use Faulancer\Http\Request;
use PHPUnit\Framework\TestCase;

/**
 * File RequestTest.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class RequestTest extends TestCase
{

    /** @var Request */
    protected $request;

    public function setUp()
    {
        $this->request = new Request();
    }

    public function testSetGetUri()
    {
        $this->request->setUri('/stub/test');

        $this->assertTrue(is_string($this->request->getUri()));
        $this->assertSame('/stub/test', $this->request->getUri());
    }

    public function testSetGetMethod()
    {
        $this->request->setMethod('POST');

        $this->assertTrue(is_string($this->request->getMethod()));
        $this->assertSame('POST', $this->request->getMethod());
    }

}