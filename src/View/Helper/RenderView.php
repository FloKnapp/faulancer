<?php

namespace Faulancer\View\Helper;

use Faulancer\Exception\ConfigInvalidException;
use Faulancer\Exception\FileNotFoundException;
use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class RenderView | RenderView.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
class RenderView extends AbstractViewHelper
{

    /**
     * Render a subview
     *
     * @param ViewController $view
     * @param string         $template
     * @param array          $variables
     * @return string
     *
     * @throws FileNotFoundException
     * @throws ConfigInvalidException
     * @throws ServiceNotFoundException
     */
    public function __invoke(ViewController $view, string $template = '', array $variables = [])
    {
        $subview = new ViewController();

        if (!empty($view->getTemplatePath())) {
            $subview->setTemplatePath($view->getTemplatePath());
        }

        $subview->setTemplate( $template );
        $subview->setVariables( $variables );
        return $subview->render();
    }

}