<?php
/**
 * Class RenderView | RenderView.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class RenderView
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