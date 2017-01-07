<?php

namespace Faulancer\Controller;

use Faulancer\ORM\ORM;
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

    /** @var array */
    private $viewArray = [];

    /**
     * Returns the service locator
     *
     * @return ServiceLocator
     */
    public function getServiceLocator()
    {
        return ServiceLocator::instance();
    }

    /**
     * Returns the view controller
     *
     * @return ViewController
     */
    public function getView()
    {
        $calledClass = get_called_class();

        if (in_array($calledClass, array_keys($this->viewArray))) {
            return $this->viewArray[$calledClass];
        }

        $viewController = new ViewController();
        $this->viewArray[$calledClass] = $viewController;

        return $viewController;
    }

    /**
     * Returns the orm/entity manager
     *
     * @return ORM
     */
    public function getDatabase()
    {
        return $this->getServiceLocator()->get(ORM::class);
    }

    /**
     * Render view with given template
     *
     * @param  string $template
     * @param  array $variables
     * @return string
     */
    public function render(string $template = '', $variables = [])
    {
        return $this->getView()->setTemplate($template)->setVariables($variables)->render();
    }

}