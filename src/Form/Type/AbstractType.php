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
     * @codeCoverageIgnore
     */
    public function getDefaultValidator()
    {
        return $this->defaultValidator;
    }

    /**
     * @param AbstractValidator $validator
     * @codeCoverageIgnore
     */
    public function setDefaultValidator(AbstractValidator $validator)
    {
        $this->defaultValidator = $validator;
    }

    /**
     * @param ValidatorChain $validatorChain
     * @codeCoverageIgnore
     */
    public function setValidatorChain(ValidatorChain $validatorChain)
    {
        $this->validatorChain = $validatorChain;
    }

    /**
     * @return boolean|null
     * @codeCoverageIgnore
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
     * @return void
     * @codeCoverageIgnore
     */
    public function removeValidators()
    {
        $this->setValidatorChain(null);
        $this->setDefaultValidator(null);
    }

    /**
     * @return array|string
     * @codeCoverageIgnore
     */
    public function getErrorMessages()
    {
        if (empty($this->formErrorDecoration)) {
            return $this->errorMessages;
        }

        $def = $this->formErrorDecoration;

        if (empty($this->errorMessages)) {
            return false;
        }

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
     * @codeCoverageIgnore
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
     * @return self
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getLabel() :string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return self
     */
    public function setLabel(string $label)
    {
        $this->label = $label;
        return $this;
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
     * @return self
     * @codeCoverageIgnore
     */
    public function setValue(string $value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getName() :string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString() :string
    {
        return str_replace('  ', ' ', $this->element);
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