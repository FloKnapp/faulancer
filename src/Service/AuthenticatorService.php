<?php
/**
 * Class AuthenticatorService | AuthenticatorService.php
 * @package Faulancer\Auth
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Service;

use Faulancer\Controller\Controller;
use Faulancer\ORM\User\Entity as UserEntity;
use Faulancer\ServiceLocator\ServiceInterface;
use Faulancer\Session\SessionManager;

/**
 * Class AuthenticatorService
 */
class AuthenticatorService implements ServiceInterface
{

    /** @var Controller */
    protected $controller;

    /** @var DbService */
    protected $orm;

    /** @var Config */
    protected $config;

    /** @var string */
    protected $redirectAfterAuth;

    /**
     * Authenticator constructor.
     * @param Controller $controller
     * @param Config     $config
     */
    public function __construct(Controller $controller, Config $config)
    {
        $this->controller = $controller;
        $this->config     = $config;
    }

    /**
     * @param UserEntity $user
     * @return bool
     * @codeCoverageIgnore
     */
    public function loginUser(UserEntity $user)
    {
        /** @var UserEntity $userData */
        $userData = $this->controller
            ->getDb()
            ->fetch(get_class($user))
            ->where('login', '=', $user->login)
            ->andWhere('password', '=', $user->password)
            ->one();

        if ($userData instanceof UserEntity) {
            $this->saveUserInSession($userData);
            return $this->controller->redirect($this->redirectAfterAuth);
        }

        /** @var SessionManagerService $sessionManager */
        $sessionManager = $this->controller->getServiceLocator()->get(SessionManagerService::class);
        $sessionManager->setFlashbag('loginError', 'No valid username/password combination found.');
        return $this->redirectToAuthentication();
    }

    /**
     * @return bool
     */
    public function redirectToAuthentication()
    {
        /** @var Config $config */
        $config  = $this->controller->getServiceLocator()->get(Config::class);
        $authUrl = $config->get('auth:authUrl');

        return $this->controller->redirect($authUrl);
    }

    /**
     * @param string $uri
     * @codeCoverageIgnore
     */
    public function redirectAfterAuthentication(string $uri)
    {
        $this->redirectAfterAuth = $uri;
    }

    /**
     * @param array $roles
     * @return bool
     */
    public function isAuthenticated(array $roles)
    {
        /** @var UserEntity $user */
        $user = $this->getUserFromSession();

        if (!$user instanceof UserEntity) {
            return false;
        }

        foreach ($user->roles as $userRole) {

            if (in_array($userRole->roleName, $roles, true)) {
                return true;
            }

        }

        return false;
    }

    /**
     * @param UserEntity $user
     */
    public function saveUserInSession(UserEntity $user)
    {
        $this->controller->getSessionManager()->set('user', $user->id);
    }

    /**
     * @return UserEntity
     */
    public function getUserFromSession()
    {
        $id = $this->controller->getSessionManager()->get('user');

        /** @var UserEntity $user */
        $user = $this->controller->getDb()->fetch(UserEntity::class, $id);
        return $user;
    }

}