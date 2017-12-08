<?php

namespace Faulancer\Form\Type\Base;

use Faulancer\Form\Type\AbstractType;

/**
 * Class TextArea
 *
 * @package Faulancer\Form\Type\Base
 * @author  Florian Knapp <office@florianknapp.de>
 */
class TextArea extends AbstractType
{

    /** @var string */
    protected $inputType = 'textarea';

    /** @var string */
    protected $element = '';

    /**
     * @return self
     */
    public function create()
    {
        parent::create();

        $output = '<' . $this->inputType . ' ';

        foreach ($this->definition['attributes'] as $attr => $value) {

            if ($attr === 'value') { // There is no value attribute for textarea fields
                continue;
            }

            if ($attr === 'name' && !empty($this->getName())) {
                continue;
            }

            $output .= $attr . '="' . $value . '" ';

        }

        if (!empty($this->getName())) {
            $output .= ' name="' . $this->getName() . '"';
        }

        $output .= '>';

        if (!empty($this->getValue())) {
            $output .= $this->getValue();
        }

        $output .= '</textarea>';

        $this->element = $output;

        return $this;

    }

}