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
        $this->setSuccessUrl('testSuccess');
        $this->setErrorUrl('testError');

        if ($this->getForm()->isValid()) {
            return $this->getSuccessUrl();
        }

        return $this->getErrorUrl();

    }

}