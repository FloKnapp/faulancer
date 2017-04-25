<?php
/**
 * Class Select | Select.php
 * @package Faulancer\Form\Type\Base
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Form\Type\Base;

use Faulancer\Form\Type\AbstractType;

/**
 * Class Select
 */
class Select extends AbstractType
{

    protected $inputType = 'select';

    public function create()
    {
        $this->setLabel($this->definition['label']);

        if (!empty($this->definition['validator'])) {
            $this->setValidator(new $this->definition['validator']($this));
        }

        $output = '<' . $this->inputType;

        foreach ($this->definition['attributes'] as $attr => $value) {
            $output .= ' ' . $attr . '="' . $value . '" ';
        }

        $output .= '>';

        foreach ($this->definition['options'] as $val => $opt) {

            $selected = ($val === $this->definition['selected']) ? '" selected="selected"' : '"';

            $output .= '<option value="' . $val . $selected . '>' . $opt . '</option>';
        }

        $output .= '</select>';

        $this->element = $output;

        return $this;
    }

}