<?php

namespace Faulancer\Test\Mocks\View;

use Faulancer\View\AbstractViewHelper;

/**
 * File StubViewHelperWithConstructor.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class StubViewHelperWithConstructor extends AbstractViewHelper
{

    protected $value;

    /**
     * StubViewHelperWithConstructor constructor.
     *
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

}