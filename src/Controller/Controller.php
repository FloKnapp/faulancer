<?php

namespace Faulancer\Controller;

use Faulancer\Service\ORM;
use Faulancer\View\ViewController;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class Controller
 *
 * @package Faulancer\Controller
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

    /**
     * @return ViewController
     */
    public function getView()
    {
        return new ViewController();
    }

    /**
     * @return Database
     */
    public function getDatabase()
    {
        return $this->getServiceLocator()->get(ORM::class);
    }

    /**
     * @param  string $template
     * @param  array $variables
     * @return string
     */
    public function render(string $template = '', $variables = [])
    {
        return $this->getView()->setTemplate($template)->setVariables($variables)->render();
    }

}