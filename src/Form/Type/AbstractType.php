<?php
/**
 * Class AbstractType
 * @package Faulancer\Form\Type\Base
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Form\Type;

use Faulancer\Form\Validator\AbstractValidator;
use Faulancer\Service\RequestService;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\Form\Validator\ValidatorChain;

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
    protected $element = '';

    /** @var array */
    protected $errorMessages = [];

    /** @var AbstractValidator|null */
    protected $defaultValidator = null;

    /** @var ValidatorChain */
    protected $validatorChain = null;

    /**
     * AbstractType constructor.
     * @param array $definition
     * @param array $formErrorDecoration
     */
    public function __construct(array $definition, array $formErrorDecoration = [])
    {
        $this->definition          = $definition;
        $this->formErrorDecoration = $formErrorDecoration;
    }

    /**
     * @return AbstractValidator|null
     */
    public function getDefaultValidator()
    {
        return $this->defaultValidator;
    }

    /**
     * @param AbstractValidator $validator
     */
    public function setDefaultValidator(AbstractValidator $validator)
    {
        $this->defaultValidator = $validator;
    }

    /**
     * @param ValidatorChain $validatorChain
     */
    public function setValidatorChain(ValidatorChain $validatorChain)
    {
        $this->validatorChain = $validatorChain;
    }

    /**
     * @return boolean|null
     */
    public function isValid()
    {
        if (!empty($this->validatorChain)) {
            return $this->validatorChain->validate();
        } elseif (!empty($this->getDefaultValidator())) {
            return $this->getDefaultValidator()->validate();
        }

        return null;
    }

    /**
     * @return array|string
     */
    public function getErrorMessages()
    {
        if (empty($this->formErrorDecoration)) {
            return $this->errorMessages;
        }

        $def = $this->formErrorDecoration;

        return $def['containerPrefix']
            . $def['containerItemPrefix']
            . implode(
                $def['containerItemSuffix'] . $def['containerItemPrefix'],
                $this->errorMessages
            )
            . $def['containerItemSuffix']
            . $def['containerSuffix'];

    }

    /**
     * @param array $messages
     */
    public function setErrorMessages(array $messages)
    {
        $this->errorMessages = $messages;
    }

    /**
     * @return string
     */
    public function getType() :string
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
    public function getLabel() :string
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
    public function getValue() :string
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
    public function getName() :string
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
     * @return string
     */
    public function __toString() :string
    {
        return $this->element;
    }

    /**
     * @return boolean
     */
    protected function isPost() :bool
    {
        return ServiceLocator::instance()->get(RequestService::class)->isPost();
    }

    /**
     * @return self
     */
    abstract public function create();

}