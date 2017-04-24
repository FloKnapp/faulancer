<?php

/**
 * Class AbstractType
 * @package Faulancer\Form\Type
 */
namespace Faulancer\Form\Type;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class AbstractType
 */
abstract class AbstractType
{

    /** @var string */
    protected $outputPattern = '';

    /** @var string */
    protected $errorMessage = '';

    /** @var AbstractValidator|null */
    protected $validator = null;

    /**
     * @param AbstractValidator $validator
     */
    public function setValidator(AbstractValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param string $message
     */
    public function setErrorMessage(string $message)
    {
        $this->errorMessage = $message;
    }

    /**
     * @param array $definition
     * @return self
     */
    abstract public function build(array $definition);

}