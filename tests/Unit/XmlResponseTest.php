<?php

namespace Faulancer\Test\Unit;

use Faulancer\Http\XmlResponse;
use Faulancer\Service\XmlResponseService;
use PHPUnit\Framework\TestCase;

/**
 * Class XmlResponseTest
 * @package Unit
 * @author  Florian Knapp <office@florianknapp.de>
 */
class XmlResponseTest extends TestCase
{

    /** @var XmlResponse */
    protected $response;

    /**
     * Set up response object to easily reuse in every test
     */
    public function setUp()
    {
        $response = $this->createPartialMock(XmlResponseService::class, ['setResponseHeader']);
        $response->method('setResponseHeader')->will($this->returnValue(true));
        $this->response = $response;
    }

    public function testConvertingToXml()
    {
        $data = [
            'name' => 'url',
            [
                'name' => 'content',
                'value' => 'Test'
            ]
        ];

        $this->response->setContent($data);

        $expected = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<url>
  <content>Test</content>
</url>
XML;

        self::assertSame($expected, $this->response->getContent());

    }

}