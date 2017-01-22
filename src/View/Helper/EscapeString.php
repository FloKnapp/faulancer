<?php
/**
 * Class EscapeString | EscapeString.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class EscapeString
 */
class EscapeString extends AbstractViewHelper
{

    /**
     * Escape a string value
     *
     * @param ViewController $view
     * @param string         $string
     * @return string
     */
    public function __invoke(ViewController $view, string $string)
    {
        return stripslashes(strip_tags($string));
    }

}