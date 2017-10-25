<?php
/**
 * Class AbstractControllerServiceFactory | AbstractControllerServiceFactory.phptory.php
 * @package Faulancer\Service\Factory
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Service\Factory;

use Faulancer\Http\Request;
use Faulancer\Service\AbstractControllerService;
use Faulancer\ServiceLocator\FactoryInterface;
use Faulancer\ServiceLocator\ServiceLocatorInterface;

/**
 * Class AbstractControllerServiceFactory
 * @codeCoverageIgnore
 */
class AbstractControllerServiceFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AbstractControllerService
     * @codeCoverageIgnore
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $request = new Request();

        if (empty($_SERVER['REQUEST_URI']) && empty($_SERVER['REQUEST_METHOD'])) {
            $request->setMethod('GET');
            $request->setPath('/');
        } else {
            $request->createFromHeaders();
        }

        return new AbstractControllerService($request);
    }

}