<?php

namespace Faulancer\Controller;

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
     * @param  string $template
     * @param  array $variables
     * @return string
     */
    public function render(string $template = '', $variables = [])
    {
        return $this->getView()->setTemplate($template)->setVariables($variables)->render();
    }

}