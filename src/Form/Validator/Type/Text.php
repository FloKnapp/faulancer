<?php

namespace Faulancer\Form\Validator\Type;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class Text
 * @package Faulancer\Form\Validator\Type
 */
class Text extends AbstractValidator
{

    /** @var string */
    protected $errorMessage = 'validator_invalid_text';

    /**
     * @param string $data
     * @return boolean
     */
    public function process($data)
    {
        return !empty($data) && is_string($data) && !is_numeric($data);
    }

}