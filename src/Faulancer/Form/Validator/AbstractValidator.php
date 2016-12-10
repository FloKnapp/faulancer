<?php

namespace Faulancer\Form\Validator;

/**
 * Class AbstractValidator
 *
 * @package Faulancer\Form\Validator
 * @author Florian Knapp <office@florianknapp.de>
 */
abstract class AbstractValidator
{

    /**
     * @return array
     */
    public abstract function validationOptions();

}