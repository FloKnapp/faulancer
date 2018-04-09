<?php

namespace Faulancer\View\Helper;

use Faulancer\Exception\FileNotFoundException;
use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class ParentTemplate
 *
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
class ParentTemplate extends AbstractViewHelper
{

    /**
     * Define the parent template
     *
     * @param string $template
     *
     * @throws FileNotFoundException
     * @throws ServiceNotFoundException
     */
    public function __invoke(string $template = '')
    {
        $viewParent = new ViewController();

        if (!empty($this->getView()->getTemplatePath())) {
            $viewParent->setTemplatePath($this->view->getTemplatePath());
        }

        $viewParent->setTemplate($template);
        $this->view->setParentTemplate($viewParent);
    }

}