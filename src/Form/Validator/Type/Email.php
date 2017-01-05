<?php

namespace Faulancer\Form\Validator\Type;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class Email
 * @package Faulancer\Form\Validator\Type
 */
class Email extends AbstractValidator
{

    public function process(string $data)
    {
        return true;
    }

}