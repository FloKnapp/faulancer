<?php
/**
 * Class Email | Email.php
 * @package Faulancer\Form\Validator\Base
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Form\Validator\Base;

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