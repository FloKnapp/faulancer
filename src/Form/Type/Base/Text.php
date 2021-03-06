<?php

namespace Faulancer\Form\Type\Base;

use Faulancer\Form\Type\AbstractType;

/**
 * Class Text
 *
 * @package Faulancer\Form\Type\Base
 * @author Florian Knapp <office@florianknapp.de>
 */
class Text extends AbstractType
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

        $output = '<' . $this->inputType . ' ';

        foreach ($this->definition['attributes'] as $attr => $value) {

            if (!empty($this->getValue()) && $attr === 'value') {
                continue;
            }

            if ($attr === 'type' && !empty($this->getType())) {
                continue;
            }

            $output .= $attr . '="' . $value . '" ';

        }

        if (!empty($this->getValue())) {
            $output .= ' value="' . $this->getValue() . '"';
        }

        if (!empty($this->getType())) {
            $output .= ' type="' . $this->getType() . '"';
        }

        $output .= ' />';

        $this->element = $output;

        return $this;
    }

}