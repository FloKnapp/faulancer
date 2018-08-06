<?php

namespace Faulancer\Test\Integration;

use Faulancer\Controller\Controller;
use Faulancer\Exception\ConfigInvalidException;
use Faulancer\Exception\RouteInvalidException;
use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\Http\Http;
use Faulancer\Http\Request;
use Faulancer\Http\Response;
use Faulancer\Service\AuthenticatorService;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceInterface;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\Session\SessionManager;
use Faulancer\View\ViewController;
use PHPUnit\Framework\TestCase;

/**
 * File ControllerTest.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class ControllerTest extends TestCase
{

    /** @var Controller */
    protected $controller;

    public function setUp()
    {
        $serviceLocator   = ServiceLocator::instance();
        $this->controller = $serviceLocator->get(Controller::class);
    }

    /**
     * Test get view
     */
    public function testGetView()
    {
        $this->assertInstanceOf(ViewController::class, $this->controller->getView());
    }

    /**
     * Test get orm
     */
    public function testGetDbService()
    {
        $this->assertInstanceOf(\Faulancer\Service\DbService::class, $this->controller->getDb());
    }

    /**
     * Test get session manager
     */
    public function testGetSessionManager()
    {
        $this->assertInstanceOf(SessionManager::class, $this->controller->getSessionManager());
    }

    /**
     * Test get request
     */
    public function testGetRequest()
    {
        $this->assertInstanceOf(Request::class, $this->controller->getRequest());
    }

    /**
     * Test render view
     * @runInSeparateProcess
     */
    public function testRender()
    {
        $this->assertStringStartsWith('Test', $this->controller->render('/stubView.phtml')->getContent());
    }

    /**
     * @throws ServiceNotFoundException
     */
    public function testRenderNonExistentTemplate()
    {
        /** @var Response|\PHPUnit_Framework_MockObject_MockObject $response */
        $response = $this->createPartialMock(Response::class, ['setResponseHeader']);
        $response->method('setResponseHeader')->will($this->returnValue(true));

        ServiceLocator::instance()->set(Response::class, $response);

        self::assertContains(
            '/nonexistent.phtml" not found',
            $this->controller->render('/nonexistent.phtml')->getContent()
        );
    }

    /**
     * @throws ServiceNotFoundException
     * @throws ConfigInvalidException
     */
    public function testRenderInvalidConfiguration()
    {
        /** @var Response|\PHPUnit_Framework_MockObject_MockObject $response */
        $response = $this->createPartialMock(Response::class, ['setResponseHeader']);
        $response->method('setResponseHeader')->will($this->returnValue(true));

        $serviceLocator = ServiceLocator::instance();

        $confFile = require __DIR__ . '/../Fixture/config/app_invalid.conf.php';

        /** @var Config $config */
        $config = $serviceLocator->get(Config::class);
        $config->set($confFile, null, true);

        $serviceLocator->set(Response::class, $response);
        $serviceLocator->set(Config::class, $config);

        self::assertContains(
            '/stubViewer.phtml" not found',
            $this->controller->render('/stubViewer.phtml')->getContent()
        );
    }

    /**
     * Test redirect
     */
    public function testRedirect()
    {
        /** @var Http|\PHPUnit_Framework_MockObject_MockObject $uriMock */
        $uriMock = $this->createPartialMock(Http::class, ['terminate']);
        $uriMock->method('terminate')->will($this->returnValue(true));

        ServiceLocator::instance()->set('\Faulancer\Http\Http', $uriMock);

        $result = $this->controller->redirect('/test');

        $this->assertTrue($result);
    }

    /**
     * Test require auth
     */
    public function testRequireAuthSuccess()
    {
        /** @var AuthenticatorService|\PHPUnit_Framework_MockObject_MockObject $authMock */
        $authMock = $this->createPartialMock(AuthenticatorService::class, ['isPermitted', 'redirectToAuthentication']);
        $authMock->method('isPermitted')->will($this->returnValue(true));
        $authMock->method('redirectToAuthentication')->will($this->returnValue(true));

        ServiceLocator::instance()->set('Faulancer\Service\AuthenticatorService', $authMock);

        $this->assertTrue($this->controller->isPermitted(['test']));
    }

    /**
     * Test redirect to auth
     */
    public function testRequireAuthRedirectToLogin()
    {
        /** @var ServiceInterface|\PHPUnit_Framework_MockObject_MockObject $authMock */
        $authMock = $this->createPartialMock(AuthenticatorService::class, ['isPermitted', 'redirectToAuthentication']);
        $authMock->method('isPermitted')->will($this->returnValue(false));
        $authMock->method('redirectToAuthentication')->will($this->returnValue(false));

        ServiceLocator::instance()->set('Faulancer\Service\AuthenticatorService', $authMock);

        $this->assertFalse($this->controller->isPermitted(['test']));
    }

    public function testGetSameView()
    {
        $controller = $this->controller->getView();

        $this->assertSame($this->controller->getView(), $controller);
    }

    public function testSetGetFlashMessage()
    {
        $this->controller->setFlashMessage('test', 'test_value');
        $this->assertSame('test_value', $this->controller->getFlashMessage('test'));
    }

    public function testRoute()
    {
        $this->assertSame('/', $this->controller->route('home'));
    }

    public function testRouteAbsolute()
    {
        $this->assertSame('/', $this->controller->route('home', [], true));
    }

    public function testRouteWithParams()
    {
        $this->assertSame('/test', $this->controller->route('home', ['test']));
    }

    public function testRouteInvalid()
    {
        $this->expectException(RouteInvalidException::class);

        $this->controller->route('non-existent');
    }

}