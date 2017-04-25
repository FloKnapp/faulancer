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
        $response = Client::post('https://httpbin.org', ['Content-Type' => 'application/json'], ['test' => true]);
        self::assertStringStartsWith('<!DOCTYPE html>', $response);
    }

    public function testClientResponseWithHeaders()
    {
        $headers = [
            'Content-Base: text/html'
        ];

        $response = Client::get('https://www.google.de', $headers);
        self::assertStringStartsWith('<!doctype', $response);
    }

}