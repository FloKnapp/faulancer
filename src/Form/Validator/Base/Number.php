<?php

namespace Faulancer\Form\Validator\Base;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class Number
 *
 * @package Faulancer\Form\Validator\Base
 * @author Florian Knapp <office@florianknapp.de>
 */
class Number extends AbstractValidator
{

    /**
     * The error message as key for translation
     * @var string
     */
    protected $errorMessage = 'validator_invalid_number';

    /**
     * Validate type number
     *
     * @param string $data
     *
     * @return bool
     */
    public function process($data)
    {
        return !!preg_match('/([0-9\/-_.]+)/', $data);
    }

}