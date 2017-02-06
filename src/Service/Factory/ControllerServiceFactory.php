<?php
/**
 * Class ControllerServiceFactory | ControllerServiceFactory.php
 * @package Faulancer\Service\Factory
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Service\Factory;

use Faulancer\Http\Request;
use Faulancer\Service\ControllerService;
use Faulancer\ServiceLocator\FactoryInterface;
use Faulancer\ServiceLocator\ServiceLocatorInterface;

/**
 * Class ControllerServiceFactory
 * @codeCoverageIgnore
 */
class ControllerServiceFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ControllerService
     * @codeCoverageIgnore
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $request = new Request();

        if (empty($_SERVER['REQUEST_URI']) && empty($_SERVER['REQUEST_METHOD'])) {
            $request->setMethod('GET');
            $request->setUri('/');
        } else {
            $request->createFromHeaders();
        }

        return new ControllerService($request);
    }

}