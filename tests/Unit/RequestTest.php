<?php

namespace Faulancer\Test\Unit;

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

    public function testGetPostData()
    {
        $_POST['key1'] = 'value1';
        $_POST['key2'] = 'value2';
        $_POST['key3'] = 'value3';
        $_POST['key4'] = 'value4';
        $_POST['key5'] = 'value5';

        $request = new Request();

        $request->setMethod('POST');
        $this->assertTrue($request->isPost());

        foreach ($request->getPostData() as $key => $value) {
            $this->assertTrue(is_string($value));
            $this->assertSame($value, $_POST[$key]);
        }

        unset($_POST);

        $this->assertEmpty($request->getPostData());
    }

    public function testCreateFromHeaders()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/stub';

        $request = new Request();
        $request->createFromHeaders();

        $this->assertSame('/stub', $request->getUri());
        $this->assertSame('POST', $request->getMethod());

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '';

        $request = new Request();
        $request->createFromHeaders();

        $this->assertEmpty($request->getUri());
        $this->assertSame('GET', $request->getMethod());
        $this->assertTrue($request->isGet());

    }

    public function testWithQuery()
    {
        $request = new Request();

        $_SERVER['REQUEST_URI'] = '/stub?test=tester';
        $request->createFromHeaders();

        $this->assertNotEmpty($request->getQuery());
    }

    public function testSetGetBody()
    {
        $request = new Request();

        $request->setBody(['test' => true]);
        self::assertSame(['test' => true], $request->getBody());
    }

    public function testSetPostData()
    {
        $request = new Request();

        $request->setPostData(['test' => true]);
        self::assertSame(['test' => true], $_POST);
    }


}