<?php

namespace Faulancer\View\Helper;

use Faulancer\View\AbstractViewHelper;

/**
 * Class RenderBlock
 *
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
class RenderBlock extends AbstractViewHelper
{

    /**
     * Render a defined block from variable
     *
     * @param string         $block
     * @param string         $defaultValue
     * @return array|string
     */
    public function __invoke(string $block, string $defaultValue = '')
    {
        if($this->view->getVariable($block) === '') {
            return $defaultValue;
        }
        return trim($this->view->getVariable($block));
    }

}