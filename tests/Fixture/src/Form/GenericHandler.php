<?php

namespace Faulancer\Fixture\Form;

use Faulancer\Form\AbstractFormHandler;
use Faulancer\Session\SessionManager;

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
            return true;
        }

        return false;

    }

}