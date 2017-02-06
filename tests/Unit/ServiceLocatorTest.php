<?php

namespace Faulancer\Test\Unit;

use Faulancer\Fixture\Service\StubServiceWithoutFactory;
use Faulancer\ServiceLocator\ServiceInterface;
use PHPUnit\Framework\TestCase;
use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\Fixture\Service\Factory\StubServiceFactory;
use Faulancer\Fixture\Service\StubService;
use Symfony\Component\EventDispatcher\Tests\Service;

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
    
    public function testOverrideService()
    {
        /** @var StubService $originalService */
        $originalService = $this->serviceLocator->get(StubService::class);

        $this->assertSame('here', $originalService->getDependency());

        /** @var ServiceInterface|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->createPartialMock(StubService::class, ['getDependency']);
        $mock->method('getDependency')->will($this->returnValue(true));

        $this->serviceLocator->set('Faulancer\Fixture\Service\StubService', $mock);

        /** @var StubService $mockedResult */
        $mockedResult = $this->serviceLocator->get(StubService::class);

        $this->assertTrue($mockedResult->getDependency());

        $originalService = $this->serviceLocator->get(StubService::class);

        $this->assertSame('here', $originalService->getDependency());
    }

}