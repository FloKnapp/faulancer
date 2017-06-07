<?php
/**
 * Class AuthenticatorServiceTest | AuthenticatorTest.php
 * @package Integration
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Integration;

use Faulancer\Fixture\Entity\RoleAnonymousEntity;
use Faulancer\Fixture\Entity\RoleAuthorEntity;
use Faulancer\Fixture\Entity\UserEntity;
use Faulancer\ORM\User\Entity;
use Faulancer\ORM\User\Role;
use Faulancer\Service\AuthenticatorService;
use Faulancer\Service\HttpService;
use Faulancer\Service\SessionManagerService;
use Faulancer\ServiceLocator\ServiceInterface;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\Session\SessionManager;
use ORM\EntityFetcher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\Tests\Service;

/**
 * Class AuthenticatorTest
 */
class AuthenticatorServiceTest extends TestCase
{

    /** @var AuthenticatorService */
    protected $authenticator;

    /** @var SessionManagerService */
    protected $sessionManager;

    public function setUp()
    {
        /** @var ServiceInterface|\PHPUnit_Framework_MockObject_MockObject $httpMock */
        $httpMock = $this->createPartialMock(HttpService::class, ['triggerRedirect']);
        $httpMock->method('triggerRedirect')->will($this->returnValue(true));

        ServiceLocator::instance()->set('Faulancer\Service\HttpService', $httpMock);

        /** @var AuthenticatorService $authenticator */
        $this->authenticator = ServiceLocator::instance()->get(AuthenticatorService::class);

        /** @var SessionManagerService sessionManager */
        $this->sessionManager = ServiceLocator::instance()->get(SessionManagerService::class);
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
        $this->markTestSkipped('Incosistent');

        $user = new Entity();
        $user->id = 1;

        $this->authenticator->saveUserInSession($user);

        $this->assertSame(1, $this->sessionManager->get('user'));
    }

    public function testGetUserFromSession()
    {
        $this->markTestSkipped('Incosistent');
        $this->assertInstanceOf(EntityFetcher::class, $this->authenticator->getUserFromSession());
    }

    /**
     * Test if user can be authenticated
     */
    public function testIsAuthenticated()
    {
        $user = new UserEntity();
        $user->roles[] = new RoleAuthorEntity();

        /** @var AuthenticatorService|\PHPUnit_Framework_MockObject_MockObject $authMock */
        $authMock = $this->createPartialMock(AuthenticatorService::class, ['getUserFromSession']);
        $authMock->method('getUserFromSession')->will($this->returnValue($user));

        $result = $authMock->isAuthenticated(['author']);

        $this->assertTrue($result);

    }

    /**
     * Test if user hasn't sufficient rights
     */
    public function testIsAuthenticatedFails()
    {
        $user = new UserEntity();
        $user->roles[] = new RoleAnonymousEntity();

        /** @var AuthenticatorService|\PHPUnit_Framework_MockObject_MockObject $authMock */
        $authMock = $this->createPartialMock(AuthenticatorService::class, ['getUserFromSession']);
        $authMock->method('getUserFromSession')->will($this->returnValue($user));

        $result = $authMock->isAuthenticated(['author']);

        $this->assertFalse($result);
    }

    /**
     * Test if user isn't logged in
     */
    public function testIsAuthenticatedNoUserObject()
    {

        $user = new \stdClass();

        /** @var AuthenticatorService|\PHPUnit_Framework_MockObject_MockObject $authMock */
        $authMock = $this->createPartialMock(AuthenticatorService::class, ['getUserFromSession']);
        $authMock->method('getUserFromSession')->will($this->returnValue($user));

        $result = $authMock->isAuthenticated(['author']);

        $this->assertFalse($result);
    }

}