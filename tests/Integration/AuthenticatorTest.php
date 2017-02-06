<?php
/**
 * Class AuthenticatorServiceTest | AuthenticatorTest.php
 * @package Integration
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Integration;

use Faulancer\Controller\Controller;
use Faulancer\Http\Request;
use Faulancer\ORM\User\Entity;
use Faulancer\ORM\User\RoleEntity;
use Faulancer\Service\AuthenticatorService;
use Faulancer\Service\Config;
use Faulancer\Service\ControllerService;
use Faulancer\Service\DbService;
use Faulancer\Service\HttpService;
use Faulancer\Service\RequestService;
use Faulancer\ServiceLocator\ServiceInterface;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\Session\SessionManager;
use ORM\EntityFetcher;
use PHPUnit\Framework\TestCase;

/**
 * Class AuthenticatorTest
 */
class AuthenticatorServiceTest extends TestCase
{

    /** @var AuthenticatorService */
    protected $authenticator;

    public function setUp()
    {
        /** @var ServiceInterface|\PHPUnit_Framework_MockObject_MockObject $httpMock */
        $httpMock = $this->createPartialMock(HttpService::class, ['triggerRedirect']);
        $httpMock->method('triggerRedirect')->will($this->returnValue(true));

        ServiceLocator::set('Faulancer\Service\HttpService', $httpMock);

        /** @var AuthenticatorService $authenticator */
        $this->authenticator = ServiceLocator::instance()->get(AuthenticatorService::class);
    }

    public function testRedirectToAuthentication()
    {
        $this->assertTrue($this->authenticator->redirectToAuthentication());
    }

    /**
     * @runInSeparateProcess
     */
    public function testSaveUserInSession()
    {
        $user = new Entity();
        $user->id = 1;

        $this->authenticator->saveUserInSession($user);

        $this->assertSame(1, SessionManager::instance()->get('user'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetUserFromSession()
    {
        $this->assertInstanceOf(EntityFetcher::class, $this->authenticator->getUserFromSession());
    }

    public function testIsAuthenticated()
    {

        $userRole = new \stdClass();
        $userRole->roleName = 'author';

        $user = new \stdClass();
        $user->id = 1;
        $user->firstname = 'Test';
        $user->lastname = 'Test';
        $user->roles[] = $userRole;

        /** @var AuthenticatorService|\PHPUnit_Framework_MockObject_MockObject $authMock */
        $authMock = $this->createPartialMock(AuthenticatorService::class, ['getUserFromSession']);
        $authMock->method('getUserFromSession')->will($this->returnValue($user));

        $result = $authMock->isAuthenticated(['author']);

        $this->assertTrue($result);

    }

    public function testIsAuthenticatedFails()
    {
        $userRole = new \stdClass();
        $userRole->roleName = 'admin';

        $user = new \stdClass();
        $user->id = 1;
        $user->firstname = 'Test';
        $user->lastname = 'Test';
        $user->roles[] = $userRole;

        /** @var AuthenticatorService|\PHPUnit_Framework_MockObject_MockObject $authMock */
        $authMock = $this->createPartialMock(AuthenticatorService::class, ['getUserFromSession']);
        $authMock->method('getUserFromSession')->will($this->returnValue($user));

        $result = $authMock->isAuthenticated(['author']);

        $this->assertFalse($result);
    }

}