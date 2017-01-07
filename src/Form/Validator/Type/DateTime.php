<?php
/**
 * Class DateTime
 * @package Faulancer\Form\Validator
 */
namespace Faulancer\Form\Validator\Type;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class DateTime
 */
class DateTime extends AbstractValidator
{

    /**
     * The error message as key for translation
     * @var string
     */
    protected $errorMessage = 'validator_invalid_datetime_format';

    /**
     * Proof date validation with the \DateTime object
     * @param string $data
     * @return boolean
     */
    public function process($data)
    {
        try {
            $date = new \DateTime($data);
            return true;
        } catch (\Exception $e) {}

        return false;
    }

}