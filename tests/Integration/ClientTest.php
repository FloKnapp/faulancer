<?php

namespace Faulancer\Test\Integration;

use Faulancer\Http\Client;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientTest
 * @package Faulancer\Test\Unit
 */
class ClientTest extends TestCase
{

    public function testClientPlainResponse()
    {
        $response = Client::get('https://www.google.de');
        self::assertStringStartsWith('<!doctype', $response);
    }

    public function testClientPostRequest()
    {
        $response = Client::post('https://www.posttestserver.com/', ['Content-Type' => 'application/json'], ['test' => true]);
        self::assertStringStartsWith('<html>', $response);
    }

    public function testClientResponseWithHeaders()
    {
        $headers = [
            'Content-Type: text/html'
        ];

        $response = Client::get('https://www.google.de', $headers);
        self::assertStringStartsWith('<!doctype', $response);
    }

}