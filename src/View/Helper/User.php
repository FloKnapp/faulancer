<?php
/**
 * Class User | User.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\Service\Authenticator;
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
        return SessionManager::instance()->get('user') > 0;
    }

    public function get()
    {
        /** @var Authenticator $authenticator */
        $authenticator = $this->getServiceLocator()->get(Authenticator::class);
        return $authenticator->getUserFromSession();
    }

}