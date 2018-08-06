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
use Faulancer\Http\Http;
use Faulancer\ORM\User\Entity;
use Faulancer\Service\AuthenticatorService;
use Faulancer\ServiceLocator\ServiceInterface;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\Session\SessionManager;
use PHPUnit\Framework\TestCase;

/**
 * Class AuthenticatorTest
 */
class AuthenticatorServiceTest extends TestCase
{

    /** @var AuthenticatorService */
    protected $authenticator;

    /** @var SessionManager */
    protected $sessionManager;

    public function setUp()
    {
        /** @var ServiceInterface|\PHPUnit_Framework_MockObject_MockObject $httpMock */
        $httpMock = $this->createPartialMock(Http::class, ['triggerRedirect']);
        $httpMock->method('triggerRedirect')->will($this->returnValue(true));

        ServiceLocator::instance()->set('Faulancer\Http\Http', $httpMock);

        /** @var AuthenticatorService $authenticator */
        $this->authenticator = ServiceLocator::instance()->get(AuthenticatorService::class);

        /** @var SessionManager sessionManager */
        $this->sessionManager = ServiceLocator::instance()->get(SessionManager::class);
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

        $this->assertSame(1, $this->sessionManager->get('user'));
    }

    public function testGetUserFromSession()
    {
        $this->assertNull($this->authenticator->getUserFromSession());
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

        $result = $authMock->isPermitted(['author']);

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

        $result = $authMock->isPermitted(['author']);

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

        $result = $authMock->isPermitted(['author']);

        $this->assertNull($result);
    }

}