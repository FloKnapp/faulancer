<?php

namespace Faulancer\Test\Unit;

use Faulancer\Exception\InvalidArgumentException;
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

    /**
     * Setup for request object
     */
    public function setUp()
    {
        $this->request = new Request();
    }

    /**
     * Test setting and getting path
     */
    public function testSetGetPath()
    {
        $this->request->setPath('/stub/test');

        self::assertTrue(is_string($this->request->getPath()));
        self::assertSame('/stub/test', $this->request->getPath());
    }

    /**
     * Test setting and getting of method
     */
    public function testSetGetMethod()
    {
        $this->request->setMethod('POST');

        self::assertTrue(is_string($this->request->getMethod()));
        self::assertSame('POST', $this->request->getMethod());
    }

    /**
     * Test setting and getting post data
     */
    public function testGetPostData()
    {
        $_POST['key1'] = 'value1';
        $_POST['key2'] = 'value2';
        $_POST['key3'] = 'value3';
        $_POST['key4'] = 'value4';
        $_POST['key5'] = 'value5';

        $request = new Request();

        $request->setMethod('POST');
        self::assertTrue($request->isPost());

        foreach ($request->getPostData() as $key => $value) {
            self::assertTrue(is_string($value));
            self::assertSame($value, $_POST[$key]);
        }

        unset($_POST);

        self::assertEmpty($request->getPostData());
    }

    /**
     * Test creating request automatically from given headers
     */
    public function testCreateFromHeaders()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/stub';
        $_SERVER['HTTP_HOST'] = 'velebeat.com';

        $request = new Request();
        $request->createFromHeaders();

        self::assertSame('/stub', $request->getPath());
        self::assertSame('POST', $request->getMethod());

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '';

        $request = new Request();
        $request->createFromHeaders();

        self::assertEmpty($request->getPath());
        self::assertSame('GET', $request->getMethod());
        self::assertTrue($request->isGet());

    }

    /**
     * Test query
     */
    public function testWithQuery()
    {
        $request = new Request();

        $_SERVER['REQUEST_URI'] = '/stub?test=tester';
        $request->createFromHeaders();

        self::assertNotEmpty($request->getQuery());
    }

    /**
     * Test setting of body content
     */
    public function testSetGetBody()
    {
        $request = new Request();

        $request->setBody(['test' => true]);
        self::assertSame(['test' => true], $request->getBody());
    }

    /**
     * Test setting of post data
     */
    public function testSetPostData()
    {
        $request = new Request();

        $request->setPostData(['test' => true]);
        self::assertSame(['test' => true], $_POST);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testGetParam()
    {
        $request = new Request();
        $request->setQuery('testQuery=testValue');
        $request->setPostData(['testPost' => 'testValue']);

        self::assertSame('testValue', $request->getParam('testQuery'));
        self::assertSame('testValue', $request->getParam('testPost'));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testRetrieveQueryParam()
    {
        $request = new Request();
        $request->setUri('/test?query=true');

        self::assertSame('true', $request->getParam('query'));
    }
}