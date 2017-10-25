<?php
/**
 * Class ParentTemplate | ParentTemplate.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class ParentTemplate
 */
class ParentTemplate extends AbstractViewHelper
{

    /**
     * Define the parent template
     *
     * @param ViewController $view
     * @param string         $template
     */
    public function __invoke(ViewController $view, string $template = '')
    {
        $viewParent = new ViewController();

        if (!empty($view->getTemplatePath())) {
            $viewParent->setTemplatePath($view->getTemplatePath());
        }

        $viewParent->setTemplate($template);
        $view->setParentTemplate($viewParent);
    }

}