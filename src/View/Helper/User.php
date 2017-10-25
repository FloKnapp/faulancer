<?php
/**
 * Class Entity | UserEntityEntity.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\ORM\User\Entity;
use Faulancer\Service\AuthenticatorService;
use Faulancer\Service\SessionManagerService;
use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class Entity
 */
class User extends AbstractViewHelper
{

    /** @var string */
    protected $entity = '';

    public function __invoke(ViewController $view, string $entity = '')
    {
        $this->entity = $entity;
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
        return $authenticator->getUserFromSession($this->entity);
    }

    public function isPermitted(array $roles)
    {
        /** @var AuthenticatorService $authenticator */
        $authenticator = $this->getServiceLocator()->get(AuthenticatorService::class);

        return $authenticator->isPermitted($roles);
    }

}