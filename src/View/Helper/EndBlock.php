<?php

namespace Faulancer\View\Helper;

use Faulancer\View\AbstractViewHelper;

/**
 * Class EndBlock
 *
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
class EndBlock extends AbstractViewHelper
{

    /**
     * Block closing handler
     *
     * @param string         $name
     */
    public function __invoke(string $name = '')
    {
        $content = ob_get_contents();
        $this->view->getParentTemplate()->setVariable($name, $content);

        unset($this->blockName);
        unset($this->blockContent);

        ob_end_clean();
    }

}