<?php
/**
 * Class Number
 * @package Faulancer\Form\Validator\Type
 */
namespace Faulancer\Form\Validator\Type;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class Number
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
     * @param string $data
     * @return boolean
     */
    public function process($data)
    {
        return is_int($data) || is_float($data);
    }

}