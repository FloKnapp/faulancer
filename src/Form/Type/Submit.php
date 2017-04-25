<?php
/**
 * Class Text
 * @package Faulancer\Form\Type
 */
namespace Faulancer\Form\Type;

/**
 * Class Text
 */
class Submit extends AbstractType
{

    protected $definition = [];

    protected $type = 'input';

    protected $element = '';

    /**
     * @return string
     */
    public function create()
    {
        $output = '<' . $this->type;

        $this->inputLabel = $this->definition['label'];

        foreach ($this->definition['attributes'] as $attr => $value) {
            $output .= ' ' . $attr . '="' . $value . '" ';
        }

        $output .= '/>';

        $this->element = $output;

        return $this;
    }

}