<?php

namespace Faulancer\View\Helper;

use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class Escape | Escape.php
 *
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
class Escape extends AbstractViewHelper
{

    /**
     * Gets called within view
     *
     * @param ViewController $view The view controller
     * @param string         $data The data which should be escaped
     *
     * @return mixed
     */
    public function __invoke(ViewController $view, string $data)
    {
        return $this->escape($data);
    }

    /**
     * Escape string
     *
     * @param string $data The data which should be escaped
     *
     * @return string
     */
    protected function escape(string $data)
    {
        return htmlspecialchars($data);
    }

}