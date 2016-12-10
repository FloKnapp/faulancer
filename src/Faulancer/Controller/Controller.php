<?php

namespace Faulancer\Controller;

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

}