<?php

namespace Faulancer\View\Helper;

use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\Translate\Translator;
use Faulancer\View\AbstractViewHelper;

/**
 * Class Translate
 *
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
class Translate extends AbstractViewHelper
{

    /**
     * Translate a string
     *
     * @param string $string
     * @param array  $value
     * @return string
     *
     * @throws ServiceNotFoundException
     */
    public function __invoke(string $string, array $value = [])
    {
        $translator = new Translator();
        return $translator->translate($string, $value);
    }

}