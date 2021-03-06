<?php

namespace Faulancer\Service;

use Faulancer\Controller\Controller;
use Faulancer\Exception\DbException;
use Faulancer\Exception\InvalidArgumentException;
use Faulancer\ORM\User\Entity;
use Faulancer\Security\Crypt;
use Faulancer\ServiceLocator\ServiceInterface;

/**
 * Class AuthenticatorService | AuthenticatorService.php
 *
 * @package Faulancer\Service
 * @author  Florian Knapp <office@florianknapp.de>
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
     *
     * @param Controller $controller
     * @param Config     $config
     */
    public function __construct(Controller $controller, Config $config)
    {
        $this->controller = $controller;
        $this->config     = $config;
    }

    /**
     * Login user with given entity
     *
     * @param Entity $user
     * @param bool   $shouldBeActive
     * @param string $redirectUrl
     *
     * @return bool
     */
    public function loginUser(Entity $user, $shouldBeActive = true, $redirectUrl = '')
    {
        /** @var Entity $userData */
        $userData = $this->controller
            ->getDb()
            ->fetch(get_class($user))
            ->where('login', '=', $user->login)
            ->orWhere('email', '=', $user->login)
            ->one();

        if (empty($userData)) {
            $this->controller->setFlashMessage('error.login', 'invalid_username_or_password');
            return $this->redirectToAuthentication();
        }

        if ($shouldBeActive && $userData->active !== 1) {
            $this->controller->setFlashMessage('error.active', 'user_is_not_activated');
            return $this->redirectToAuthentication();
        }

        $passOk = Crypt::verifyPassword($user->password, $userData->password);

        if ($passOk && $userData instanceof Entity) {

            $this->saveUserInSession($userData);

            if ($redirectUrl) {
                return $this->controller->redirect($redirectUrl);
            }

            if ($userData->roles[0]->roleName === 'registered') {
                return $this->controller->redirect($this->controller->route('user'));
            } else {
                return $this->controller->redirect($this->controller->route('admin'));
            }

        }

        $this->controller->setFlashMessage('error.login', 'invalid_username_or_password');

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
     * @param array $roles
     * @return bool
     */
    public function isPermitted(array $roles): bool
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
     * @codeCoverageIgnore
     */
    public function saveUserInSession(Entity $user)
    {
        $this->controller->getSessionManager()->set('user', $user->id);
    }

    /**
     * @param string $entity
     * @return Entity     *
     * @codeCoverageIgnore
     */
    public function getUserFromSession(string $entity = '')
    {
        $id = $this->controller->getSessionManager()->get('user');

        if (empty($id)) {
            return null;
        }

        /** @var Entity $user */
        if (!empty($entity)) {
            $user = $this->controller->getDb()->fetch($entity, $id);
        } else {
            $user = $this->controller->getDb()->fetch(Entity::class, $id);
        }

        return $user;
    }

}