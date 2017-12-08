<?php

namespace Faulancer\Form\Type;

use Faulancer\Exception\ConfigInvalidException;
use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\Form\Validator\AbstractValidator;
use Faulancer\Service\Config;
use Faulancer\Service\RequestService;
use Faulancer\Service\SessionManagerService;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\Form\Validator\ValidatorChain;
use Faulancer\ServiceLocator\ServiceLocatorAwareInterface;
use Faulancer\ServiceLocator\ServiceLocatorInterface;
use Faulancer\Session\SessionManager;

/**
 * Class AbstractType
 *
 * @package Faulancer\Form\Type\Base
 * @author Florian Knapp <office@florianknapp.de>
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

    /** @var string */
    protected $formIdentifier = '';

    /** @var AbstractValidator|null */
    protected $defaultValidator = null;

    /** @var ValidatorChain */
    protected $validatorChain = null;

    /** @var array */
    protected $formErrorDecoration = [];

    /** @var ServiceLocatorInterface */
    protected $serviceLocator;

    /**
     * AbstractType constructor.
     *
     * @param array $definition
     * @param array $formErrorDecoration
     * @param string $formIdentifier
     */
    public function __construct(array $definition, array $formErrorDecoration = [], string $formIdentifier = '')
    {
        $this->definition          = $definition;
        $this->formErrorDecoration = $formErrorDecoration;
        $this->formIdentifier      = $formIdentifier;
    }

    /**
     * Get default validator
     *
     * @return AbstractValidator|null
     */
    public function getDefaultValidator()
    {
        return $this->defaultValidator;
    }

    /**
     * Set default validator
     *
     * @param AbstractValidator $validator
     */
    public function setDefaultValidator(AbstractValidator $validator)
    {
        $this->defaultValidator = $validator;
    }

    /**
     * Set validator chain
     *
     * @param ValidatorChain $validatorChain
     */
    public function setValidatorChain(ValidatorChain $validatorChain)
    {
        $this->validatorChain = $validatorChain;
    }

    /**
     * Validate field by validator chain or default validator
     *
     * @return bool|null
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
     * Remove bound validators
     *
     * @return void
     */
    public function removeValidators()
    {
        $this->setValidatorChain(null);
        $this->setDefaultValidator(null);
    }

    /**
     * Get error messages
     *
     * @return array|string
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

        if (strpos($def['containerPrefix'], '{name}') !== false) {

            $name = $this->definition['attributes']['name'];
            $def['containerPrefix'] = str_replace('{name}', $name, $def['containerPrefix']);

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
     * Set error messages
     *
     * @param array $messages
     */
    public function setErrorMessages(array $messages)
    {
        $this->errorMessages = $messages;
    }

    /**
     * Get field type
     *
     * @return string
     */
    public function getType() :string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
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
     *
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
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return self
     */
    public function setValue($value) :self
    {
        $this->value = $value;
        return $this;
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
     *
     * @return self
     */
    public function setName(string $name) :self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Add field attribute
     *
     * @param string $key
     * @param string $value
     *
     * @return self
     */
    public function addAttribute(string $key, string $value) :self
    {
        if (!empty($this->definition['attributes'][$key])) {
            $this->definition['attributes'][$key] = $this->definition['attributes'][$key] . ' ' . $value;
        } else {
            $this->definition['attributes'][$key] = $value;
        }

        return $this;
    }

    /**
     * @return bool
     *
     * @throws ServiceNotFoundException
     */
    protected function isPost() :bool
    {
        return ServiceLocator::instance()->get(RequestService::class)->isPost();
    }

    /**
     * @return self
     */
    public function create()
    {
        if (empty($this->getLabel()) && !empty($this->definition['label'])) {
            $this->setLabel($this->definition['label']);
        }

        if (empty($this->getType()) && !empty($this->definition['type'])) {
             $this->setType($this->definition['type']);
        }

        if (empty($this->getValue()) && !empty($this->definition['value'])) {
            $this->setValue($this->definition['value']);
        }

        if (empty($this->getName()) && !empty($this->definition['name'])) {
            $this->setName($this->definition['name']);
        }

        $this->_translateLabelsAndPlaceholders();

        return $this;
    }

    /**
     * @return string
     */
    public function __toString() :string
    {
        $this->create();
        return str_replace(['  ', ' >'], [' ', '>'], $this->element);
    }

    /**
     * @return bool
     */
    private function _translateLabelsAndPlaceholders() :bool
    {
        if (!empty($this->definition['attributes']['placeholder'])) {
            $this->_translateType('placeholder');
        } else if (!empty($this->definition['label'])) {
            $this->_translateType('label');
        } else {
            return false;
        }

        return true;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    private function _translateType(string $type) :bool
    {
        /** @var Config $config */
        $config = $this->_getServiceLocator()->get(Config::class);

        /** @var SessionManager $sessionManager */
        $sessionManager = $this->_getServiceLocator()->get(SessionManagerService::class);
        $lang           = $sessionManager->get('language');

        try {

            $trans = $config->get('translation:' . $lang);

            if (!empty($trans['form_' . $this->getName()])) {

                $transText = $trans['form_' . $this->getName()];

                switch ($type) {

                    case 'label':
                        $this->setLabel($transText);
                        break;

                    case 'placeholder':
                        $this->definition['attributes']['placeholder'] = $transText;
                        break;

                }

            }

        } catch (ConfigInvalidException $e) {
            return false;
        }

        return true;
    }

    public function getFormIdentifier()
    {
        return $this->formIdentifier;
    }

    /**
     * @return ServiceLocatorInterface
     */
    private function _getServiceLocator()
    {
        return ServiceLocator::instance();
    }

}