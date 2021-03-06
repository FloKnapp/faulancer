<?php

namespace Faulancer\Test\Integration;

use Faulancer\Exception\ConfigInvalidException;
use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\Service\Config;
use Faulancer\Service\Factory\ConfigFactory;
use Faulancer\ServiceLocator\ServiceLocator;
use PHPUnit\Framework\TestCase;

/**
 * File ConfigTest.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class ConfigTest extends TestCase
{

    /** @var ServiceLocator */
    protected $serviceLocator;

    public function setUp()
    {
        $this->serviceLocator = ServiceLocator::instance();
    }

    /**
     * @throws ConfigInvalidException
     * @throws ServiceNotFoundException
     */
    public function testSetGet()
    {
        /** @var Config $config */
        $config = $this->serviceLocator->get(Config::class);

        $config->set('test', 'value');
        $this->assertSame('value', $config->get('test'));
    }

    public function testSetGetMultiple()
    {
        /** @var Config $config */
        $config = $this->serviceLocator->get(Config::class);

        $this->assertSame($config, $this->serviceLocator->get(Config::class));

        $config->set('redis.login', [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
            'key4' => 'value4',
            'key5' => 'value5'
        ]);

        $value = $config->get('redis.login');

        $this->assertNotEmpty($value);
        $this->assertTrue(is_array($value));
    }

    public function testGetKeyInvalid()
    {
        /** @var Config $config */
        $config = $this->serviceLocator->get(Config::class);

        self::assertNull($config->get('nonExistent'));
    }

    public function testSetKeyInvalid()
    {
        /** @var Config $config */
        $config = $this->serviceLocator->get(Config::class);

        self::assertTrue($config->set('testKey', 'testValue'));
        self::assertFalse($config->set('testKey', 'otherValue'));
    }

    public function testSetKeyArray()
    {
        $data = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
            'key4' => 'value4',
            'key5' => 'value5',
        ];

        /** @var Config $config */
        $config = $this->serviceLocator->get(Config::class);

        $config->set($data);

        foreach ($data as $key => $value) {

            $this->assertTrue(is_string($config->get($key)));
            $this->assertSame($data[$key], $value);

        }

    }

    public function testGetRecursive()
    {
        /** @var Config $config */
        $config = $this->serviceLocator->get(Config::class);
        $this->assertSame('test', $config->get('recursiveTest:layer:layer2'));
    }

    public function testGetRecursiveNonExistent()
    {
        /** @var Config $config */
        $config = $this->serviceLocator->get(Config::class);
        $this->assertNull($config->get('recursiveTest:layer:layer2:layerNonExistent'));
    }

}