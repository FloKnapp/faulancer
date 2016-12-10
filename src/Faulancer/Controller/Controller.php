<?php

namespace Faulancer\Controller;

use Core\View\ViewController;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * File Controller.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
abstract class Controller
{

    /**
     * @return ServiceLocator
     */
    public function getServiceLocator()
    {
        return ServiceLocator::instance();
    }

    public function getView()
    {
        return ViewController::instance();
    }

}