<?php

namespace Faulancer\Test\Integration;

use Faulancer\Exception\FileNotFoundException;
use Faulancer\Http\Http;
use Faulancer\Http\Request;
use Faulancer\Service\AuthenticatorService;
use Faulancer\Service\ControllerService;
use Faulancer\Service\HttpService;
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

    /** @var ControllerService */
    protected $controller;

    public function setUp()
    {
        $serviceLocator   = ServiceLocator::instance();
        $this->controller = $serviceLocator->get(ControllerService::class);
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
     * Test redirect
     */
    public function testRedirect()
    {
        /** @var HttpService|\PHPUnit_Framework_MockObject_MockObject $uriMock */
        $uriMock = $this->createPartialMock(Http::class, ['terminate']);
        $uriMock->method('terminate')->will($this->returnValue(true));

        ServiceLocator::instance()->set('HttpService', $uriMock);

        $result = $this->controller->redirect('/test');

        $this->assertTrue($result);
    }

    /**
     * Test require auth
     */
    public function testRequireAuthSuccess()
    {
        /** @var AuthenticatorService|\PHPUnit_Framework_MockObject_MockObject $authMock */
        $authMock = $this->createPartialMock(AuthenticatorService::class, ['isAuthenticated', 'redirectToAuthentication']);
        $authMock->method('isAuthenticated')->will($this->returnValue(true));
        $authMock->method('redirectToAuthentication')->will($this->returnValue(true));

        ServiceLocator::instance()->set('Faulancer\Service\AuthenticatorService', $authMock);

        $this->assertTrue($this->controller->requireAuth(['test']));
    }

    /**
     * Test redirect to auth
     */
    public function testRequireAuthRedirectToLogin()
    {
        /** @var ServiceInterface|\PHPUnit_Framework_MockObject_MockObject $authMock */
        $authMock = $this->createPartialMock(AuthenticatorService::class, ['isAuthenticated', 'redirectToAuthentication']);
        $authMock->method('isAuthenticated')->will($this->returnValue(false));
        $authMock->method('redirectToAuthentication')->will($this->returnValue(false));

        ServiceLocator::instance()->set('Faulancer\Service\AuthenticatorService', $authMock);

        $this->assertFalse($this->controller->requireAuth(['test']));
    }

    public function testGetSameView()
    {
        $controller = $this->controller->getView();

        $this->assertSame($this->controller->getView(), $controller);
    }

}