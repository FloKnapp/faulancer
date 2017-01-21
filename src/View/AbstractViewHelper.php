<?php
/**
 * Class AbstractViewHelper
 *
 * @method  __invoke()
 * @package Faulancer\View
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View;

use Faulancer\Exception\ConstantMissingException;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class AbstractViewHelper
 * @method __invoke()
 */
abstract class AbstractViewHelper
{

    /**
     * Render a view with given template and variables
     * @param  string $template
     * @param  array  $variables
     * @return string
     * @throws ConstantMissingException
     */
    protected function renderView($template = '', array $variables = []) :string
    {
        /** @var Config $config */
        $config = $this->getServiceLocator()->get(Config::class);

        $templatePath = $config->get('viewsRoot') . '/helper';

        return (new ViewController())->setTemplate($templatePath . $template)->setVariables($variables)->render();
    }

    /**
     * Get instance of service locator
     * @return ServiceLocator
     */
    public function getServiceLocator() :ServiceLocator
    {
        return ServiceLocator::instance();
    }

    /**
     * If view helper gets outputted return content
     * @return string
     */
    public function __toString() :string
    {
        return (string)$this->__invoke();
    }

}