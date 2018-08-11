<?php

namespace Faulancer\View\Helper;

use Faulancer\View\AbstractViewHelper;

/**
 * Class EscapeString
 *
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
class EscapeString extends AbstractViewHelper
{

    /**
     * Escape a string value
     *
     * @param string $string
     * @return string
     */
    public function __invoke(string $string)
    {
        return stripslashes(strip_tags($string));
    }

}