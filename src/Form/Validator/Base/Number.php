<?php
/**
 * Class Number | Number.php
 * @package Faulancer\Form\Validator\Base
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Form\Validator\Base;

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