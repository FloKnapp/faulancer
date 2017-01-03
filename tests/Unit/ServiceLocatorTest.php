<?php

namespace Faulancer\Test\Unit;

use Faulancer\ServiceLocator\ServiceLocator;
use PHPUnit\Framework\TestCase;
use Faulancer\Test\Mocks\Service\Factory\StubFactory;
use Faulancer\Test\Mocks\Service\StubService;

/**
 * File ServiceLocatorTest.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class ServiceLocatorTest extends TestCase
{

    /** @var ServiceLocator */
    protected $serviceLocator;

    public function setUp()
    {
        $this->serviceLocator = ServiceLocator::instance();
    }

    public function testInstance()
    {
        $this->assertInstanceOf(ServiceLocator::class, $this->serviceLocator);
    }

    public function testSameInstance()
    {
        $this->assertSame(ServiceLocator::instance(), $this->serviceLocator);
    }

    public function testGetService()
    {
        $stubService = $this->serviceLocator->get(StubService::class);
        $this->assertInstanceOf(StubService::class, $stubService);
    }

    public function testGetServiceFactory()
    {
        $stubFactory = $this->serviceLocator->get(StubFactory::class);
        $this->assertInstanceOf(StubFactory::class, $stubFactory);
    }

    public function testGetSameService()
    {
        $service1 = $this->serviceLocator->get(StubService::class);
        $service2 = $this->serviceLocator->get(StubService::class);

        $this->assertSame($service1, $service2);
    }

    public function testGetNewService()
    {
        $service1 = $this->serviceLocator->get(StubService::class);
        $service2 = $this->serviceLocator->get(StubService::class, false);

        $this->assertNotSame($service1, $service2);
    }

}