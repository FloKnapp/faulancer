<?php

namespace Faulancer\Form\Validator\Base;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class DateTime | DateTime.php
 * @package Faulancer\Form\Validator\Base
 * @author Florian Knapp <office@florianknapp.de>
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
     *
     * @param string $data
     *
     * @return bool
     */
    public function process($data)
    {
        try {
            $date = new \DateTime($data);
            return true;
        } catch (\Exception $e) {
            return false;
        }

    }

}