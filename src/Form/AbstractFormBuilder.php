<?php
/**
 * Class AbstractFormBuilder | AbstractFormBuilder.php
 * @package Faulancer\Form\Type
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Form;

use Faulancer\Exception\FormInvalidException;
use Faulancer\Exception\InvalidArgumentException;
use Faulancer\Exception\InvalidFormElementException;
use Faulancer\Form\Type\AbstractType;
use Faulancer\Http\Request;
use Faulancer\ORM\Entity;
use Faulancer\Security\Csrf;
use Faulancer\Service\RequestService;
use Faulancer\Service\SessionManagerService;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\Form\Validator\ValidatorChain;
use Faulancer\Session\SessionManager;
use Nette\Http\Session;

/**
 * Class AbstractFormBuilder
 */
abstract class AbstractFormBuilder
{

    /** @var string */
    protected $identifier = '';

    /** @var array */
    protected $formAttributes = [];

    /** @var AbstractType[] */
    protected $fields = [];

    /** @var Entity */
    protected $entity = null;

    /** @var string */
    protected $formErrorContainerPrefix = '';

    /** @var string */
    protected $formErrorContainerSuffix = '';

    /** @var string */
    protected $formErrorItemContainerPrefix = '';

    /** @var string */
    protected $formErrorItemContainerSuffix = '';

    /** @var  */
    private $confirmValue = null;

    /**
     * AbstractFormBuilder constructor
     * @param Entity $entity
     * @codeCoverageIgnore
     */
    public function __construct(Entity $entity = null)
    {
        if ($entity !== null) {
            $this->create();
            $this->setData($entity->getDataAsArray());
        }

        $this->create();
    }

    /**
     * @param string $name
     * @return AbstractType
     * @throws InvalidFormElementException
     * @codeCoverageIgnore
     */
    public function getField(string $name)
    {
        if (empty($this->fields[$name])) {
            throw new InvalidFormElementException('No field with name \'' . $name . '\' found');
        }
        return $this->fields[$name];
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
        $action = $this->formAttributes['action'] ?? '';
        $method = $this->formAttributes['method'] ?? '';
        $enctype = $this->formAttributes['enctype'] ?? 'application/x-www-form-urlencoded';
        $autocomplete = $this->formAttributes['autocomplete'] ?? 'on';

        return '<form action="' . $action . '" method="' . $method . '" enctype="' . $enctype . '" autocomplete="' . $autocomplete . '">';
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
     * @codeCoverageIgnore
     */
    public function setFormErrorContainer(string $prefix, string $suffix)
    {
        $this->formErrorContainerPrefix = $prefix;
        $this->formErrorContainerSuffix = $suffix;
    }

    /**
     * @param string $prefix
     * @param string $suffix
     * @codeCoverageIgnore
     */
    public function setFormErrorItemContainer(string $prefix, string $suffix)
    {
        $this->formErrorItemContainerPrefix = $prefix;
        $this->formErrorItemContainerSuffix = $suffix;
    }

    /**
     * @return array
     * @codeCoverageIgnore
     */
    public function getData()
    {
        $result = [];

        /** @var AbstractType $value */
        foreach ($this->fields as $key => $field) {

            if ($field->getType() === 'submit' || empty($field->getValue())) {
                continue;
            }

            // Check stored confirm value (i.e. for password repeat requests) and ignore the second field
            if ($field->getValue() === $this->confirmValue) {
                continue;
            }

            $this->confirmValue = $field->getValue();

            $result[$key] = $field->getValue();

        }

        return $result;
    }

    /**
     * @param array $data
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
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
     * @param array $definition
     * @throws InvalidArgumentException
     * @codeCoverageIgnore
     */
    protected function add(array $definition)
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
        $request  = ServiceLocator::instance()->get(RequestService::class);
        $postData = $request->getPostData();

        if ($request->isPost() && !empty($postData[$name])) {
            $typeClass->setValue($postData[$name]);
        }

        // If radio or checkbox field isn't selected, the field wouldn't
        // be send within post data so always add a validator if exists
        $isRadioOrCheckbox = $type === 'radio' || $type === 'checkbox';

        if ($isRadioOrCheckbox || ($request->isPost() && in_array($name, array_keys($postData)))) {
            $this->addValidators($typeClass, $definition);
        }

        $this->fields[$name] = $typeClass->create();
    }

    /**
     * @param AbstractType $typeClass
     * @param array        $definition
     * @throws FormInvalidException
     * @return boolean
     * @codeCoverageIgnore
     */
    private function addValidators(AbstractType &$typeClass, array $definition)
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