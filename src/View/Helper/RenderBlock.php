<?php
/**
 * Class RenderBlock | RenderBlock.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class RenderBlock
 */
class RenderBlock extends AbstractViewHelper
{

    /**
     * Render a defined block from variable
     *
     * @param ViewController $view
     * @param string         $block
     * @param string         $defaultValue
     * @return array|string
     */
    public function __invoke(ViewController $view, string $block, string $defaultValue = '')
    {
        if($view->getVariable($block) === '') {
            return $defaultValue;
        }
        return trim($view->getVariable($block));
    }

}