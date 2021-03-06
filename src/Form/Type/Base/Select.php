<?php

namespace Faulancer\Form\Type\Base;

use Faulancer\Form\Type\AbstractType;

/**
 * Class Select
 *
 * @package Faulancer\Form\Type\Base
 * @author Florian Knapp <office@florianknapp.de>
 */
class Select extends AbstractType
{

    protected $inputType = 'select';

    /**
     * @return self
     */
    public function create()
    {
        parent::create();

        $output = '<' . $this->inputType;

        foreach ($this->definition['attributes'] as $attr => $value) {

            if ($attr === 'type') {
                continue;
            }

            $output .= ' ' . $attr . '="' . $value . '"';

        }

        $output .= '>';

        $definition = $this->definition;
        $isSelected = function($val) use (&$definition) {

            if ($this->isPost() && empty($val)) {
                unset($definition['selected']);
            }

            if (!empty($this->getValue()) && $val === $this->getValue()) {
                return true;
            } elseif (!empty($definition['selected']) && $val === $definition['selected']) {
                return true;
            }

            return false;

        };

        foreach ($definition['options'] as $val => $text) {

            $selected = $isSelected($val) === true ? ' selected="selected"' : '';
            $option   = '<option value="' . $val .'"%s>' . $text . '</option>';

            $output .= sprintf($option, $selected);

        }

        $output .= '</select>';

        $this->element = $output;

        return $this;
    }

}