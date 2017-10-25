<?php

namespace Faulancer\Test\Integration;

use Faulancer\Http\Adapter\Curl;
use Faulancer\Http\Adapter\Socket;
use Faulancer\Http\Client;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientTest
 * @package Faulancer\Test\Unit
 */
class ClientTest extends TestCase
{

    public function testClientCurlPlainResponse()
    {
        $client = new Client(new Curl());

        $response = $client->get('https://www.google.de');
        self::assertStringStartsWith('<!doctype', $response);
    }

    public function testClientSocketPlainResponse()
    {
        $this->markTestSkipped('Socket still not working...');
        $client = new Client(new Socket());

        $response = $client->get('https://www.google.de');
        self::assertStringStartsWith('<!doctype', $response);
    }

    public function testClientCurlPostRequest()
    {
        $client = new Client(new Curl());
        $response = $client->post('https://httpbin.org/post', ['Content-Type' => 'application/json'], ['test' => true]);
        self::assertStringStartsWith('{
  "args": {}, 
  "data": "", 
  "files": {}, 
  "form": {
    "test": "1"
  }', $response);
    }

    public function testClientSocketPostRequest()
    {
        $this->markTestSkipped('Socket still not working...');
        $client = new Client(new Socket());
        $response = $client->post('https://httpbin.org/post', ['Content-Type' => 'application/json'], ['test' => true]);
        self::assertStringStartsWith('{
  "args": {}, 
  "data": "", 
  "files": {}, 
  "form": {
    "test": "1"
  }', $response);
    }

    public function testClientResponseWithHeaders()
    {
        $client = new Client(new Curl());

        $headers = [
            'Content-Base: text/html'
        ];

        $response = $client->get('https://www.google.de', $headers);
        self::assertStringStartsWith('<!doctype', $response);
    }

}