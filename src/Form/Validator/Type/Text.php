<?php

namespace Faulancer\Form\Validator\Type;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class Text
 * @package Faulancer\Form\Validator\Type
 */
class Text extends AbstractValidator
{
    
    protected $errorMessage = 'Bitte tragen Sie einen Text ein';

    public function process(string $data)
    {
        return !empty($data) && is_string($data);
    }

}