<?php

namespace Faulancer\View\Helper;

use Faulancer\Translate\Translator;
use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class Translate | Translate.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
class Translate extends AbstractViewHelper
{

    /**
     * Translate a string
     *
     * @param ViewController $view
     * @param string         $string
     * @param array          $value
     * @return string
     */
    public function __invoke(ViewController $view, string $string, array $value = [])
    {
        $translator = new Translator();
        return $translator->translate($string, $value);
    }

}