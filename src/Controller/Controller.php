<?php
/**
 * Class Controller
 *
 * @package Faulancer\Controller
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Controller;

use Faulancer\Service\ORM;
use Faulancer\ServiceLocator\ServiceInterface;
use Faulancer\View\ViewController;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class Controller
 */
abstract class Controller
{

    /**
     * Holds the views per controller request
     * @var array
     */
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
     * @return ORM|ServiceInterface
     */
    public function getDb()
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