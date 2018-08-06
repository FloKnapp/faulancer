<?php

namespace Faulancer\Test\Unit;

use Faulancer\Http\Response;
use PHPUnit\Framework\TestCase;

/**
 * File ResponseTest.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class ResponseTest extends TestCase
{

    /** @var Response */
    protected $response;

    /**
     * Set up response object to easily reuse in every test
     */
    public function setUp()
    {
        $response = $this->createPartialMock(Response::class, ['setResponseHeader']);
        $response->method('setResponseHeader')->will($this->returnValue(true));
        $this->response = $response;
    }

    public function testResponseCode()
    {
        $this->response->setCode(200);

        self::assertTrue(is_int($this->response->getCode()));
        self::assertSame(200, $this->response->getCode());
        self::assertSame('Ok', $this->response->getMessage());
    }

    /**
     * Test response content
     */
    public function testResponseContent()
    {
        $this->response->setContent('TestData');
        $this->assertSame('TestData', $this->response->getContent());
    }

    /**
     * Test response header for status code 404
     */
    public function test404ResponseHeader()
    {
        $this->response->setCode(404);

        self::assertSame(404, $this->response->getCode());
        self::assertSame('Not Found', $this->response->getMessage());
    }

    /**
     * Test converting from object to string
     */
    public function testToString()
    {
        $this->response->setContent('Test');
        echo $this->response;

        $this->expectOutputString('Test');
    }

    /**
     * Test custom status code and message
     */
    public function testCustomStatusCodeAndMessage()
    {
        $this->response->setCode(42);
        $this->response->setMessage('Answer Of Everything');

        self::assertSame(42, $this->response->getCode());
        self::assertSame('Answer Of Everything', $this->response->getMessage());
    }

}