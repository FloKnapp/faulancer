<?php
/**
 * Class Block | Block.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class Block
 */
class Block extends AbstractViewHelper
{

    /**
     * Block opening handler
     *
     * @param ViewController $view
     * @param string         $name
     */
    public function __invoke(ViewController $view, string $name = '')
    {
        ob_start();
    }

}