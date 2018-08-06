<?php

namespace Faulancer\Controller\Factory;

use Faulancer\Controller\Controller;
use Faulancer\Http\Request;
use Faulancer\ServiceLocator\FactoryInterface;
use Faulancer\ServiceLocator\ServiceLocatorInterface;

/**
 * Class AbstractControllerFactory
 *
 * @category Controller
 * @package  Faulancer\Factory\AbstractControllerFactory
 * @author   Florian Knapp <office@florianknapp.de>
 */
class ControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return Controller
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var Request $request */
        $request = $serviceLocator->get(Request::class);

        return new Controller($request);
    }

}