<?php
/**
 * Class Submit | Submit.php
 * @package Faulancer\Form\Type\Base
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Form\Type\Base;

use Faulancer\Form\Type\AbstractType;

/**
 * Class Submit
 */
class Submit extends AbstractType
{
    /** @var string */
    protected $inputType = 'button';

    /** @var string */
    protected $element = '';

    /**
     * @return string
     */
    public function create()
    {
        $this->setLabel($this->definition['label']);

        $output = '<' . $this->inputType;

        foreach ($this->definition['attributes'] as $attr => $value) {
            $output .= ' ' . $attr . '="' . $value . '" ';
        }

        $output .= '>' . $this->definition['label'] . '</button>';

        $this->element = $output;

        return $this;
    }

}