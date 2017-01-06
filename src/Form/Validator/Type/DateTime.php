<?php

namespace Faulancer\Form\Validator\Type;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class DateTime
 * @package Faulancer\Form\Validator
 */
class DateTime extends AbstractValidator
{

    /** @var string */
    protected $errorMessage = 'validator_invalid_datetime_format';

    /**
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