<?php

namespace Faulancer\Form\Validator;

/**
 * Class AbstractValidator
 * @package Faulancer\Form
 */
abstract class AbstractValidator
{

    protected $errorMessage = '';

    public function getMessage()
    {
        return $this->errorMessage;
    }

    public abstract function process(string $data);

}