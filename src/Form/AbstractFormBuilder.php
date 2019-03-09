<?php

namespace Faulancer\Form;

use Faulancer\Exception\FormInvalidException;
use Faulancer\Exception\InvalidArgumentException;
use Faulancer\Exception\InvalidFormElementException;
use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\Form\Type\AbstractType;
use Faulancer\Form\Validator\Base\Confirm;
use Faulancer\Http\Request;
use Faulancer\ORM\Entity;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\Form\Validator\ValidatorChain;

/**
 * Class AbstractFormBuilder
 *
 * @package Faulancer\Form\Type
 * @author Florian Knapp <office@florianknapp.de>
 */
abstract class AbstractFormBuilder
{

    /** @var string */
    protected $identifier = '';

    /** @var array */
    protected $formAttributes = [];

    /** @var AbstractType[] */
    protected $fields = [];

    /** @var Entity|null */
    protected $entity = null;

    /** @var string */
    protected $formErrorContainerPrefix = '';

    /** @var string */
    protected $formErrorContainerSuffix = '';

    /** @var string */
    protected $formErrorItemContainerPrefix = '';

    /** @var string */
    protected $formErrorItemContainerSuffix = '';

    /** @var string|null */
    private $confirmValue = null;

    /**
     * AbstractFormBuilder constructor
     * @param Entity $entity
     * @codeCoverageIgnore
     */
    public function __construct(Entity $entity = null)
    {
        $this->create();

        if ($entity !== null) {
            $this->setData($entity->getDataAsArray());
        }
    }

    /**
     * Get field object
     *
     * @param string $name
     *
     * @return AbstractType
     *
     * @throws InvalidFormElementException
     */
    public function getField(string $name)
    {
        if (empty($this->fields[$name])) {

            $trace = \debug_backtrace();
            $first = array_shift($trace);

            throw new InvalidFormElementException('No field with name "' . $name . '" found', 0, $first['line'], $first['file']);
        }

        return $this->fields[$name];
    }

    /**
     * @param AbstractType $field
     *
     * @return self
     */
    public function setField(AbstractType $field)
    {
        $this->fields[$field->getName()] = $field;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param array $attributes
     */
    public function setFormAttributes(array $attributes)
    {
        $this->formAttributes = $attributes;
    }

    /**
     * @return string
     */
    public function getFormOpen()
    {
        $unknownAttributes = '';
        $knownAttributes   = ['action', 'method', 'enctype', 'autocomplete'];

        $action       = $this->formAttributes['action'] ?? '';
        $method       = $this->formAttributes['method'] ?? '';
        $enctype      = $this->formAttributes['enctype'] ?? 'application/x-www-form-urlencoded';
        $autocomplete = $this->formAttributes['autocomplete'] ?? 'on';

        foreach ($this->formAttributes as $attr => $value) {

            if (in_array($attr, $knownAttributes)) {
                continue;
            }

            $unknownAttributes .= ' ' . $attr . '="' . $value . '" ';

        }

        if ($unknownAttributes) {
            $unknownAttributes = substr($unknownAttributes, 0, strlen($unknownAttributes) - 1);
        }

        return '<form action="' . $action . '" method="' . $method . '" enctype="' . $enctype . '" autocomplete="' . $autocomplete . '"' . $unknownAttributes . '>';
    }

    /**
     * @return string
     */
    public function getFormClose()
    {
        return '</form>';
    }

    /**
     * @param string $prefix
     * @param string $suffix
     */
    public function setFormErrorContainer(string $prefix, string $suffix)
    {
        $this->formErrorContainerPrefix = $prefix;
        $this->formErrorContainerSuffix = $suffix;
    }

    /**
     * @param string $prefix
     * @param string $suffix
     */
    public function setFormErrorItemContainer(string $prefix, string $suffix)
    {
        $this->formErrorItemContainerPrefix = $prefix;
        $this->formErrorItemContainerSuffix = $suffix;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $result = [];

        /** @var AbstractType $value */
        foreach ($this->fields as $key => $field) {

            if ($field->getType() === 'submit' || empty($field->getValue())) {
                continue;
            }

            if (in_array(Confirm::class, $field->getValidatorChain()->getValidators())) {

                // Check stored confirm value (i.e. for password repeat requests) and ignore the second field
                if ($field->getValue() === $this->confirmValue) {
                    continue;
                }

                $this->confirmValue = $field->getValue();

            }

            $result[$key] = $field->getValue();

        }

        return $result;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        foreach ($data as $key => $value) {

            if (empty($this->fields[$key]) || $this->fields[$key]->getType() === 'password') {
                continue;
            }

            $this->fields[$key]->setValue($value);

        }
    }

    /**
     * @return bool
     */
    public function isValid() :bool
    {
        $errors = [];

        /** @var AbstractType $field */
        foreach ($this->fields as $field) {

            $result = $field->isValid();

            if ($result !== null) {
                $errors[] = $result;
            }

        }

        return !in_array(false, $errors, true);
    }

    /**
     * Add a form field
     *
     * @param array $definition
     *
     * @throws InvalidArgumentException
     * @throws ServiceNotFoundException
     * @throws FormInvalidException
     */
    public function add(array $definition)
    {
        $type = $definition['attributes']['type'];
        $name = $definition['attributes']['name'];

        $namespace = '\Faulancer\Form\Type\Base\\' . ucfirst($type);

        if (!class_exists($namespace)) {
            throw new InvalidArgumentException('Requesting non existent form type ' . ucfirst($type));
        }

        $formErrorDecoration = [
            'containerPrefix'     => $this->formErrorContainerPrefix,
            'containerSuffix'     => $this->formErrorContainerSuffix,
            'containerItemPrefix' => $this->formErrorItemContainerPrefix,
            'containerItemSuffix' => $this->formErrorItemContainerSuffix
        ];

        /** @var AbstractType $typeClass */
        $typeClass = new $namespace($definition, $formErrorDecoration, $this->identifier);

        $typeClass->setName($name);
        $typeClass->setType($type);

        if (!empty($this->fields[$name]) && !empty($this->fields[$name]->getValue())) {
            $typeClass->setValue($this->fields[$name]->getValue());
        }

        /** @var Request $request */
        $request  = ServiceLocator::instance()->get(Request::class);
        $postData = $request->getPostData();

        if ($request->isPost() && !empty($postData[$name])) {
            $typeClass->setValue($postData[$name]);
        }

        // If radio or checkbox field isn't selected, the field wouldn't
        // be send within post data so always add a validator if exists
        $isRadioOrCheckbox = $type === 'radio' || $type === 'checkbox';

        if ($isRadioOrCheckbox || ($request->isPost() && in_array($name, array_keys($postData)))) {
            $this->_addValidators($typeClass, $definition);
        }

        $this->fields[$name] = $typeClass->create();
    }

    /**
     * @param AbstractType $typeClass
     * @param array        $definition
     *
     * @return bool
     *
     * @throws FormInvalidException
     */
    private function _addValidators(AbstractType &$typeClass, array $definition)
    {
        if (!empty($definition['validator'])) {

            if ($definition['validator'] === 'none') {
                return true;
            }

            $validatorChain = new ValidatorChain($typeClass);

            foreach ($definition['validator'] as $validator) {

                if (!class_exists($validator)) {
                    throw new FormInvalidException('Validator "' . $validator . '" doesn\'t exists');
                }

                $validatorChain->add(new $validator($typeClass));

            }

            $typeClass->setValidatorChain($validatorChain);

        } else {

            $validator = '\Faulancer\Form\Validator\Base\\' . ucfirst($typeClass->getType());

            if (empty($definition['validator']) && class_exists($validator)) {
                $typeClass->setDefaultValidator(new $validator($typeClass));
            }

        }

        return true;
    }

    /**
     * @return mixed
     */
    abstract protected function create();

}