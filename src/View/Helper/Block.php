<?php

namespace Faulancer\View\Helper;

use Faulancer\View\AbstractViewHelper;

/**
 * Class Block
 *
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
class Block extends AbstractViewHelper
{

    /** @var bool */
    protected static $tagOpened = false;
    protected static $content   = '';

    /**
     * Block opening handler
     *
     * @param string $name
     */
    public function __invoke(string $name = '')
    {
        if (!self::$tagOpened) {

            ob_start(function($buffer) {
                self::$content = $buffer;
            });

        } else {

            $content = ob_get_contents();
            ob_end_clean();

            $this->view->getParentTemplate()->setVariable($name, $content);

            self::$tagOpened = false;

        }

        self::$tagOpened = true;
    }

    public function __destruct()
    {
        self::$tagOpened = false;
    }

}