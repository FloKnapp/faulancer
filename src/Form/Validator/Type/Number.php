<?php

namespace Faulancer\Form\Validator\Type;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class Number
 * @package Faulancer\Form\Validator\Type
 */
class Number extends AbstractValidator
{

    /** @var string */
    protected $errorMessage = 'validator_invalid_number';

    /**
     * @param string $data
     * @return boolean
     */
    public function process($data)
    {
        return is_int($data) || is_float($data);
    }

}