<?php
/**
 * Class Email | Email.php
 * @package Faulancer\Form\Validator\Type
 */
namespace Faulancer\Form\Validator\Type;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class Email
 */
class Email extends AbstractValidator
{

    /**
     * The error message as key for translation
     * @var string
     */
    protected $errorMessage = 'validator_invalid_email';

    /**
     * Validate email with filter_var
     * @param $data
     * @return boolean
     */
    public function process($data)
    {
        return !!filter_var($data, FILTER_VALIDATE_EMAIL);
    }

}