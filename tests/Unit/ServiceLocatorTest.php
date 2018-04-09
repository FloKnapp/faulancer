<?php

namespace Faulancer\Test\Unit;

use Faulancer\Fixture\Service\StubServiceWithoutFactory;
use Faulancer\ServiceLocator\ServiceInterface;
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

    /**
     * Setup service locator for reusing
     */
    public function setUp()
    {
        $this->serviceLocator = ServiceLocator::instance();
    }

    /**
     * Test if service locator is an instance of service locator
     */
    public function testInstance()
    {
        self::assertInstanceOf(ServiceLocator::class, $this->serviceLocator);
    }

    /**
     * Test singleton functionality
     */
    public function testSameInstance()
    {
        self::assertSame(ServiceLocator::instance(), $this->serviceLocator);
    }

    /**
     * Test general retrieving of a service
     *
     * @throws ServiceNotFoundException
     */
    public function testGetService()
    {
        $stubService = $this->serviceLocator->get(StubService::class);
        self::assertInstanceOf(StubService::class, $stubService);
        self::assertNotEmpty($stubService->getDependency());
        self::assertSame('here', $stubService->getDependency());
    }

    /**
     * Test getting a factory directly
     *
     * @throws ServiceNotFoundException
     */
    public function testGetServiceFactory()
    {
        $stubFactory = $this->serviceLocator->get(StubServiceFactory::class);
        self::assertInstanceOf(StubService::class, $stubFactory);
    }

    /**
     * Test services for same instance
     *
     * @throws ServiceNotFoundException
     */
    public function testGetSameService()
    {
        $service1 = $this->serviceLocator->get(StubService::class);
        $service2 = $this->serviceLocator->get(StubService::class);

        self::assertSame($service1, $service2);
    }

    /**
     * Test retrieving a new instance of a service
     *
     * @throws ServiceNotFoundException
     */
    public function testGetNewService()
    {
        $service1 = $this->serviceLocator->get(StubService::class);
        $service2 = $this->serviceLocator->get(StubService::class, false);

        self::assertNotSame($service1, $service2);
    }

    /**
     * Test creating of nonexistent service
     *
     * @throws ServiceNotFoundException
     */
    public function testMissingService()
    {
        $this->expectException(ServiceNotFoundException::class);
        $result = ServiceLocator::instance()->get('NonExistentService');
        self::assertNull($result);
    }

    /**
     * Test creating of service without a factory
     *
     * @throws ServiceNotFoundException
     */
    public function testServiceWithoutFactory()
    {
        $result = ServiceLocator::instance()->get(StubServiceWithoutFactory::class);
        self::assertInstanceOf(StubServiceWithoutFactory::class, $result);
    }

    /**
     * Test creating and destroying the service locator
     */
    public function testCreationAndDestroying()
    {
        $serviceLocator= ServiceLocator::instance();
        ServiceLocator::destroy();

        self::assertNotSame(ServiceLocator::instance(), $serviceLocator);

        $serviceLocatorNew = ServiceLocator::instance();
        self::assertSame(ServiceLocator::instance(), $serviceLocatorNew);
    }

    /**
     * Test override of services (for testing purposes)
     *
     * @throws ServiceNotFoundException
     */
    public function testOverrideService()
    {
        /** @var StubService $originalService */
        $originalService = $this->serviceLocator->get(StubService::class);

        self::assertSame('here', $originalService->getDependency());

        /** @var ServiceInterface|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->createPartialMock(StubService::class, ['getDependency']);
        $mock->method('getDependency')->will($this->returnValue(true));

        $this->serviceLocator->set(StubService::class, $mock);

        /** @var StubService $mockedResult */
        $mockedResult = $this->serviceLocator->get(StubService::class);

        self::assertTrue($mockedResult->getDependency());

        $originalService = $this->serviceLocator->get(StubService::class);

        self::assertSame('here', $originalService->getDependency());
    }

}