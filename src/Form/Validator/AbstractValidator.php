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
     * Provide validation options for the form handler
     *
     * -- Example of return value -------------------------------
     *
     *   return [
     *     '{field}'  => ['not_empty', 'is_string', 'is_email'],
     *     '{field2}' => ['not_empty', 'is_number']
     *   ];
     *
     * ----------------------------------------------------------
     *
     * @return array
     */
    public abstract function validationOptions();

}