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

        $this->assertStringStartsWith('<!doctype', $response);
    }

    public function testClientResponseWithHeaders()
    {

        $headers = [
            'Content-Type: text/html'
        ];

        $response = Client::get('https://www.google.de', $headers);

        $this->assertStringStartsWith('<!doctype', $response);
    }

}