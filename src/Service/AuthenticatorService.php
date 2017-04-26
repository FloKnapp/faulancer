<?php
/**
 * Class AuthenticatorService | AuthenticatorService.php
 * @package Faulancer\Auth
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Service;

use Faulancer\Controller\AbstractController;
use Faulancer\ORM\User\Entity;
use Faulancer\ServiceLocator\ServiceInterface;

/**
 * Class AuthenticatorService
 */
class AuthenticatorService implements ServiceInterface
{

    /** @var AbstractController */
    protected $controller;

    /** @var DbService */
    protected $orm;

    /** @var Config */
    protected $config;

    /** @var string */
    protected $redirectAfterAuth;

    /**
     * Authenticator constructor.
     * @param AbstractController $controller
     * @param Config             $config
     */
    public function __construct(AbstractController $controller, Config $config)
    {
        $this->controller = $controller;
        $this->config     = $config;
    }

    /**
     * @param Entity $user
     * @return bool
     * @codeCoverageIgnore
     */
    public function loginUser(Entity $user)
    {
        /** @var Entity $userData */
        $userData = $this->controller
            ->getDb()
            ->fetch(get_class($user))
            ->where('login', '=', $user->login)
            ->andWhere('password', '=', $user->password)
            ->one();

        if ($userData instanceof Entity) {

            $sessionManager = $this->controller->getSessionManager();

            if ($sessionManager->hasFlashMessage('redirectAfterAuth')) {
                $this->redirectAfterAuth = $sessionManager->getFlashMessage('redirectAfterAuth');
            }

            $this->saveUserInSession($userData);
            return $this->controller->redirect($this->redirectAfterAuth);

        }

        /** @var SessionManagerService $sessionManager */
        $sessionManager = $this->controller->getServiceLocator()->get(SessionManagerService::class);
        $sessionManager->setFlashMessage('loginError', 'No valid username/password combination found.');
        return $this->redirectToAuthentication();
    }

    /**
     * @return bool
     */
    public function redirectToAuthentication()
    {
        $this->controller->getSessionManager()->setFlashMessage(
            'redirectAfterAuth',
            $this->controller->getRequest()->getUri()
        );

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
        /** @var Entity $user */
        $user = $this->getUserFromSession();

        if (!$user instanceof Entity) {
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
     * @param Entity $user
     */
    public function saveUserInSession(Entity $user)
    {
        $this->controller->getSessionManager()->set('user', $user->id);
    }

    /**
     * @return Entity
     */
    public function getUserFromSession()
    {
        $id = $this->controller->getSessionManager()->get('user');

        /** @var Entity $user */
        $user = $this->controller->getDb()->fetch(Entity::class, $id);
        return $user;
    }

}