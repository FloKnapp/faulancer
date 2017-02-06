<?php

namespace Faulancer\Fixture\Form;

use Faulancer\Form\AbstractFormHandler;

/**
 * Class GenericHandler
 * @package Faulancer\Fixture\Form
 */
class GenericHandler extends AbstractFormHandler
{

    public function run()
    {
        if ($this->getForm()->isValid()) {
            return 'testSuccess';
        }

        return 'testError';
    }

}