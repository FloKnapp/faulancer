<?php
/**
 * Class EndBlock | EndBlock.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\View\ViewController;

/**
 * Class EndBlock
 */
class EndBlock
{

    /**
     * Block closing handler
     *
     * @param ViewController $view
     * @param string         $name
     */
    public function __invoke(ViewController $view, string $name = '')
    {
        $content = ob_get_contents();
        $view->getParentTemplate()->setVariable($name, $content);

        unset($this->blockName);
        unset($this->blockContent);

        ob_end_clean();
    }

}