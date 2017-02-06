<?php
/**
 * Class User | User.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\Service\AuthenticatorService;
use Faulancer\Service\SessionManagerService;
use Faulancer\Session\SessionManager;
use Faulancer\View\AbstractViewHelper;

/**
 * Class User
 */
class User extends AbstractViewHelper
{

    public function __invoke()
    {
        return $this;
    }

    public function isLoggedIn()
    {
        return $this->getServiceLocator()->get(SessionManagerService::class)->get('user') > 0;
    }

    public function get()
    {
        /** @var AuthenticatorService $authenticator */
        $authenticator = $this->getServiceLocator()->get(AuthenticatorService::class);
        return $authenticator->getUserFromSession();
    }

}