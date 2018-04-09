<?php
/**
 * Class AbstractViewHelper
 *
 * @method  __invoke()
 * @package Faulancer\View
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View;

use Faulancer\Exception\ConfigInvalidException;
use Faulancer\Exception\ConstantMissingException;
use Faulancer\Exception\FileNotFoundException;
use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class AbstractViewHelper
 */
abstract class AbstractViewHelper
{

    /** @var ViewController */
    protected $view;

    /**
     * Render a view with given template and variables
     *
     * @param  string $template
     * @param  array  $variables
     *
     * @return string
     *
     * @throws ServiceNotFoundException
     * @throws ConfigInvalidException
     * @throws FileNotFoundException
     */
    protected function renderView($template = '', array $variables = []) :string
    {
        /** @var Config $config */
        $config = $this->getServiceLocator()->get(Config::class);

        $templatePath = $config->get('viewsRoot') . '/helper';

        return (new ViewController())->setTemplate($templatePath . $template)->setVariables($variables)->render();
    }

    /**
     * @param ViewController $view
     */
    public function setView(ViewController $view)
    {
        $this->view = $view;
    }

    /**
     * @return ViewController
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Get instance of service locator
     *
     * @return ServiceLocator
     */
    public function getServiceLocator() :ServiceLocator
    {
        return ServiceLocator::instance();
    }

}