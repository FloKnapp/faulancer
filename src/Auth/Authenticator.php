<?php
/**
 * Class Authenticator | Authenticator.php
 * @package Faulancer\Auth
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Auth;

use Faulancer\ORM\Entity;
use Faulancer\Session\SessionManager;

/**
 * Class Authenticator
 */
class Authenticator
{

    /**
     * Authenticator constructor.
     * @param SessionManager $sessionManager
     */
    public function __construct(SessionManager $sessionManager = null)
    {
        if (!$sessionManager) {
            $sessionManager = SessionManager::instance();
        }

        $this->sessionManager = $sessionManager;

        $this->sessionManager->set('redirectAfterAuth', $_SERVER['REQUEST_URI']);
    }

    /**
     * @param Entity $userData
     */
    public function registerUser(Entity $userData)
    {

    }

    /**
     * @param Entity $userData
     */
    public function loginUser(Entity $userData)
    {

    }

    /**
     * @param string $role
     * @return bool
     */
    public function isAuthenticated(string $role)
    {
        $userData = $this->sessionManager->get('userData');

        if (!empty($userData) && in_array($role, $userData['role'])) {
            return true;
        }

        return false;
    }

    /**
     * @param $userData
     */
    public function saveUserInSession($userData)
    {
        $this->sessionManager->set('userData', $userData);
    }

}