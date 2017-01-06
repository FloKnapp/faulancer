<?php

namespace Faulancer\Form\Validator\Type;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class Email
 * @package Faulancer\Form\Validator\Type
 */
class Email extends AbstractValidator
{

    /** @var string */
    protected $errorMessage = 'validator_invalid_email';

    /**
     * @param $data
     * @return boolean
     */
    public function process($data)
    {
        return !!filter_var($data, FILTER_VALIDATE_EMAIL);
    }

}