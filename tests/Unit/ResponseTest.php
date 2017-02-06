<?php

namespace Faulancer\Test\Unit;

use Faulancer\Http\Response;
use Faulancer\Service\ResponseService;
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

    public function setUp()
    {
        $response = $this->createPartialMock(ResponseService::class, ['setResponseHeader']);
        $response->method('setResponseHeader')->will($this->returnValue(true));
        $this->response = $response;
    }

    public function testResponseCode()
    {
        $this->response->setCode(200);

        $this->assertTrue(is_int($this->response->getCode()));
    }

    /**
     *
     */
    public function testResponseContent()
    {
        $this->response->setContent('TestData');
        $this->assertSame('TestData', $this->response->getContent());
    }

    public function testResponseHeader()
    {
        $this->response->setCode(404);
    }

    /**
     *
     */
    public function testToString()
    {
        $this->response->setContent('Test');
        echo $this->response;

        $this->expectOutputString('Test');
    }

}