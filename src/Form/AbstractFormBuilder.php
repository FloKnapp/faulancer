<?php
/**
 * Class AbstractFormBuilder | AbstractFormBuilder.php
 * @package Faulancer\Form\Type
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Form;

use Faulancer\Exception\FormInvalidException;
use Faulancer\Exception\InvalidArgumentException;
use Faulancer\Form\Type\AbstractType;
use Faulancer\Http\Request;
use Faulancer\ORM\Entity;
use Faulancer\Service\RequestService;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\Form\Validator\ValidatorChain;

/**
 * Class AbstractFormBuilder
 */
abstract class AbstractFormBuilder
{

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
     */
    public function getField(string $name)
    {
        return $this->fields[$name];
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
        return '<form action="' . $this->formAttributes['action'] . '" method="' . $this->formAttributes['method'] . '">';
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
        foreach ($this->fields as $key => $value) {

            if ($value->getType() === 'submit') {
                continue;
            }

            // Check stored confirm value (i.e. for password repeat requests)
            if ($value->getValue() === $this->confirmValue) {
                continue;
            }

            $this->confirmValue = $value->getValue();

            $result[$key] = $value->getValue();

        }

        return $result;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        foreach ($data as $key => $value) {

            if (empty($this->fields[$key])) {
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
     * @param array $definition
     * @throws InvalidArgumentException
     */
    protected function add(array $definition)
    {
        $type = $definition['attributes']['type'];
        $name = $definition['attributes']['name'];

        $namespace = '\Faulancer\Form\Type\Base\\' . ucfirst($type);

        if (class_exists($namespace)) {

            $formErrorDecoration = [
                'containerPrefix'     => $this->formErrorContainerPrefix,
                'containerSuffix'     => $this->formErrorContainerSuffix,
                'containerItemPrefix' => $this->formErrorItemContainerPrefix,
                'containerItemSuffix' => $this->formErrorItemContainerSuffix
            ];

            $typeClassNs = $namespace;

            /** @var AbstractType $typeClass */
            $typeClass = new $typeClassNs($definition, $formErrorDecoration);

        } else {
            throw new InvalidArgumentException('Requesting non existent form type ' . ucfirst($type));
        }

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
     */
    private function addValidators(AbstractType &$typeClass, array $definition)
    {
        if (!empty($definition['validator'])) {

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