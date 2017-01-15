<?php

namespace Faulancer\Test\Unit;

use Faulancer\Fixture\Service\StubServiceWithoutFactory;
use PHPUnit\Framework\TestCase;
use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\Fixture\Service\Factory\StubServiceFactory;
use Faulancer\Fixture\Service\StubService;

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
        $this->assertNotEmpty($stubService->getDependency());
        $this->assertSame('here', $stubService->getDependency());
    }
    
    public function testGetServiceFactory()
    {
        $stubFactory = $this->serviceLocator->get(StubServiceFactory::class);
        $this->assertInstanceOf(StubService::class, $stubFactory);
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

    public function testMissingService()
    {
        $this->expectException(ServiceNotFoundException::class);
        $result = ServiceLocator::instance()->get('NonExistentService');
        $this->assertNull($result);
    }

    public function testServiceWithoutFactory()
    {
        $result = ServiceLocator::instance()->get(StubServiceWithoutFactory::class);
        $this->assertInstanceOf(StubServiceWithoutFactory::class, $result);
    }

    public function testCreationAndDestroying()
    {
        $serviceLocator= ServiceLocator::instance();
        ServiceLocator::destroy();

        $this->assertNotSame(ServiceLocator::instance(), $serviceLocator);

        $serviceLocatorNew = ServiceLocator::instance();
        $this->assertSame(ServiceLocator::instance(), $serviceLocatorNew);
    }

}