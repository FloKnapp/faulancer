<?php

namespace Faulancer\Form\Type\Base;

use Faulancer\Form\Type\AbstractType;

/**
 * Class Checkbox
 *
 * @package Faulancer\Form\Type\Base
 * @author Florian Knapp <office@florianknapp.de>
 */
class Checkbox extends AbstractType
{

    /** @var string */
    protected $inputType = 'input';

    /** @var string */
    protected $element = '';

    /**
     * @return self
     */
    public function create()
    {
        parent::create();

        $output = '';

        if (!empty($this->definition['default'])) {

            $output .= '<' . $this->inputType;
            $output .= ' type="hidden"';
            $output .= ' name="' . $this->definition['attributes']['name'] . '"';
            $output .= ' ' . 'value="' . $this->definition['default'] . '"';
            $output .= '/>';

        }

        $output .= '<' . $this->inputType;

        foreach ($this->definition['attributes'] as $attr => $value) {
            $output .= ' ' . $attr . '="' . $value . '" ';
        }

        if (!empty($this->getValue()) && $this->definition['attributes']['value'] === $this->getValue()) {
            $output .= ' checked="checked"';
        } elseif (empty($this->getValue()) && isset($this->definition['checked']) && $this->definition['checked'] === true) {
            $output .= ' checked="checked"';
        }

        $output .= '/>';

        $this->element = $output;

        return $this;
    }

}