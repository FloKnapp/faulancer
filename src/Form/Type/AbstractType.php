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

    /** @var array */
    protected $definition = [];

    /** @var string */
    protected $inputType = '';

    /** @var string */
    protected $inputLabel = '';

    /** @var string */
    protected $inputValue = '';

    /** @var string */
    protected $inputName = '';

    /** @var string */
    protected $outputPattern = '';

    /** @var string */
    protected $errorMessage = '';

    /** @var string */
    protected $element = '';

    /** @var AbstractValidator|null */
    protected $validator = null;

    /**
     * AbstractType constructor.
     * @param array $definition
     */
    public function __construct(array $definition)
    {
        $this->definition = $definition;
    }

    /**
     * @return AbstractValidator|null
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * @param AbstractValidator $validator
     */
    public function setValidator(AbstractValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param string $message
     */
    public function setErrorMessage(string $message)
    {
        $this->errorMessage = $message;
    }

    /**
     * @return string
     */
    public function getInputType()
    {
        return $this->inputType;
    }

    /**
     * @param string $type
     */
    public function setInputType(string $type)
    {
        $this->inputType = $type;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->inputLabel;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label)
    {
        $this->inputLabel = $label;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->inputValue;
    }

    /**
     * @param $value
     */
    public function setValue(string $value)
    {
        $this->inputValue = $value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->inputName;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->inputName = $name;
    }

    /**
     * @return self
     */
    public function getField()
    {
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->element;
    }

    /**
     * @return self
     */
    abstract public function create();

}