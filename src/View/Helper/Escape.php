<?php

namespace Faulancer\View\Helper;

use Faulancer\View\AbstractViewHelper;

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
     * @param string $data The data which should be escaped
     *
     * @return mixed
     */
    public function __invoke(string $data)
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