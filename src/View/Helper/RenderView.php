<?php

namespace Faulancer\View\Helper;

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
     * @param string         $template
     * @param array          $variables
     * @return string
     *
     * @throws FileNotFoundException
     * @throws ServiceNotFoundException
     */
    public function __invoke(string $template = '', array $variables = [])
    {
        $subview = new ViewController();

        if (!empty($this->view->getTemplatePath())) {
            $subview->setTemplatePath($this->view->getTemplatePath());
        }

        $subview->setTemplate( $template );
        $subview->setVariables( $variables );
        return $subview->render();
    }

}