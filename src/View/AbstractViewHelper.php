<?php

namespace Faulancer\View;

use Faulancer\Exception\ConstantMissingException;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class AbstractViewHelper
 *
 * @method  __invoke()
 * @package Faulancer\View
 * @author  Florian Knapp <office@florianknapp.de>
 */
abstract class AbstractViewHelper
{

    /**
     * @param  string $template
     * @param  array  $variables
     * @return string
     * @throws ConstantMissingException
     */
    protected function renderView(string $template = '', array $variables = [])
    {
        /** @var Config $config */
        $config = $this->getServiceLocator()->get(Config::class);

        $templatePath = $config->get('viewsRoot') . '/helper';

        return (new ViewController())->setTemplate($templatePath . $template)->setVariables($variables)->render();
    }

    /**
     * @return ServiceLocator
     */
    public function getServiceLocator()
    {
        return ServiceLocator::instance();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->__invoke();
    }

}