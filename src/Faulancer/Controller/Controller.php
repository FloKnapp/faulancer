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

    public function getServiceLocator()
    {
        return new ServiceLocator();
    }

}