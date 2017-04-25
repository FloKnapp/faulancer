<?php
/**
 * Class AbstractFormBuilder
 * @package Faulancer\Form
 */
namespace Faulancer\Form;

use Faulancer\Controller\Controller;
use Faulancer\Exception\InvalidArgumentException;
use Faulancer\Form\Type\AbstractType;
use Faulancer\Http\Request;
use Faulancer\Service\RequestService;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class AbstractFormBuilder
 */
abstract class AbstractFormBuilder
{

    /** @var array */
    protected $formAttributes = [];

    /** @var array */
    protected $fields = [];

    /**
     * @return mixed
     */
    abstract public function __construct();

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

    public function isValid()
    {
        /** @var Request $request */
        $request = ServiceLocator::instance()->get(RequestService::class);

        $errors   = [];
        $postData = $request->getPostData();

        /** @var AbstractType $field */
        foreach ($this->fields as $field) {

            $field->setValue($postData[$field->getName()]);

            if ($field->getValidator() !== null) {
                $errors[] = $field->getValidator()->validate();
            }

        }

        return in_array(false, $errors, true);
    }

    /**
     * @param array $definition
     * @throws InvalidArgumentException
     */
    protected function add(array $definition)
    {
        $type = $definition['attributes']['type'];
        $name = $definition['attributes']['name'];

        if (class_exists('\Faulancer\Form\Type\\' . ucfirst($type))) {

            $typeClassNs = '\Faulancer\Form\Type\\' . ucfirst($type);
            /** @var AbstractType $typeClass */
            $typeClass = new $typeClassNs($definition);

        } else {
            throw new InvalidArgumentException('Requesting non existent form type ' . ucfirst($type));
        }

        $typeClass->setName($name);
        $typeClass->setInputType($type);

        $validator = '\Faulancer\Form\Validator\Type\\' . ucfirst($type);

        if (class_exists($validator)) {
            $typeClass->setValidator(new $validator($typeClass));
        }

        $this->fields[$name] = $typeClass->create();
    }

    /**
     * @return array
     */
    public function getData()
    {
        $result = [];

        /** @var AbstractType $value */
        foreach ($this->fields as $key => $value) {

            if ($value->getInputType() === 'submit') {
                continue;
            }

            $result[$key] = $value->getValue();

        }

        return $result;
    }

}