<?php
/**
 * Class AbstractType
 * @package Faulancer\Form\Type\Base
 * @author Florian Knapp <office@florianknapp.de>
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
    protected $type = '';

    /** @var string */
    protected $label = '';

    /** @var string */
    protected $value = '';

    /** @var string */
    protected $name = '';

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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
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