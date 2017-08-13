<?php
/**
 * Class AbstractType
 * @package Faulancer\Form\Type\Base
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Form\Type;

use Faulancer\Exception\ConfigInvalidException;
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

    /** @var array */
    protected $formErrorDecoration = [];

    /** @var ServiceLocatorInterface */
    protected $serviceLocator;

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
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return self
     * @codeCoverageIgnore
     */
    public function setValue($value)
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
     * @param string $key
     * @param string $value
     * @return self
     */
    public function addAttribute(string $key, string $value)
    {
        if (!empty($this->definition['attributes'][$key])) {
            $this->definition['attributes'][$key] = $this->definition['attributes'][$key] . ' ' . $value;
        } else {
            $this->definition['attributes'][$key] = $value;
        }

        return $this;
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
    public function create()
    {
        if (empty($this->getLabel()) && !empty($this->definition['label'])) {
            $this->setLabel($this->definition['label']);
        }

        $this->translateLabelsAndPlaceholders();

        return $this;
    }

    /**
     * @return string
     */
    public function __toString() :string
    {
        $this->create();
        return str_replace('  ', ' ', $this->element);
    }

    /**
     * @return bool
     */
    private function translateLabelsAndPlaceholders()
    {
        if (!empty($this->definition['attributes']['placeholder'])) {
            $this->translateType('placeholder');
        } else if (!empty($this->definition['label'])) {
            $this->translateType('label');
        } else {
            return false;
        }

        return true;
    }

    /**
     * @param $type
     * @return bool
     */
    private function translateType($type)
    {
        /** @var Config $config */
        $config = $this->getServiceLocator()->get(Config::class);

        /** @var SessionManager $sessionManager */
        $sessionManager = $this->getServiceLocator()->get(SessionManagerService::class);
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

    /**
     * @return ServiceLocatorInterface
     */
    private function getServiceLocator()
    {
        return ServiceLocator::instance();
    }

}