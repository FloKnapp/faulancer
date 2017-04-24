<?php
/**
 * Class AbstractFormBuilder
 * @package Faulancer\Form
 */
namespace Faulancer\Form;

use Faulancer\Controller\Controller;
use Faulancer\Exception\InvalidArgumentException;
use Faulancer\Form\Type\AbstractType;

/**
 * Class AbstractFormBuilder
 */
abstract class AbstractFormBuilder
{

    protected $setup = [];

    /**
     * @return mixed
     */
    abstract public function create();

    /**
     * @param string $name
     */
    public function getField(string $name)
    {

    }

    /**
     * @return string
     */
    public function getFormOpen()
    {
        return '<form action="' . $this->setup['action'] . '" method="' . $this->setup['action'] . '">';
    }

    /**
     * @return string
     */
    public function getFormClose()
    {
        return '</form>';
    }

    /**
     * @param array $definition
     * @throws InvalidArgumentException
     */
    protected function add(array $definition)
    {
        $type = ucfirst($definition['type']);

        if (class_exists('\Faulancer\Form\Type\\' . $type)) {

            $typeClassNs = '\Faulancer\Form\Type\\' . $type;
            /** @var AbstractType $typeClass */
            $typeClass = new $typeClassNs();

        } else {
            throw new InvalidArgumentException('Requesting non existent form type ' . $type);
        }

        $validator = '\Faulancer\Form\Validator\Type\\' . $type;

        $typeClass->setValidator(new $validator());

        $field = $typeClass->build($definition);

    }

}