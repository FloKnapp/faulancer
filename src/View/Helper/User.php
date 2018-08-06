<?php

namespace Faulancer\View\Helper;

use Faulancer\ORM\Entity;
use Faulancer\Service\AuthenticatorService;
use Faulancer\Session\SessionManager;
use Faulancer\View\AbstractViewHelper;

/**
 * Class Entity
 *
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
class User extends AbstractViewHelper
{

    /** @var string */
    protected $entity = '';

    /**
     * Initialize
     *
     * @param string $entity
     *
     * @return $this
     */
    public function __invoke(string $entity = '')
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * Check if user is logged in
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->getServiceLocator()->get(SessionManager::class)->get('user') > 0;
    }

    /**
     * Get user entity
     *
     * @return Entity
     */
    public function get()
    {
        /** @var AuthenticatorService $authenticator */
        $authenticator = $this->getServiceLocator()->get(AuthenticatorService::class);
        return $authenticator->getUserFromSession($this->entity);
    }

    /**
     * Check if user has one of given roles
     *
     * @param array $roles
     *
     * @return bool
     */
    public function isPermitted(array $roles)
    {
        /** @var AuthenticatorService $authenticator */
        $authenticator = $this->getServiceLocator()->get(AuthenticatorService::class);

        return $authenticator->isPermitted($roles);
    }

}