<?php
/**
 * Class AuthenticatorFactory | AuthenticatorFactory.php
 * @package Faulancer\Service\Factory
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Service\Factory;

use Faulancer\Controller\Controller;
use Faulancer\Http\Request;
use Faulancer\Service\Authenticator;
use Faulancer\ServiceLocator\FactoryInterface;
use Faulancer\ServiceLocator\ServiceLocatorInterface;

/**
 * Class AuthenticatorFactory
 */
class AuthenticatorFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Authenticator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $request = new Request();
        $request->createFromHeaders();

        $controller = new Controller($request);

        return new Authenticator($controller);
    }

}