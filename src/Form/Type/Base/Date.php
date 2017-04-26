<?php
/**
 * Class Date | Date.php
 * @package Faulancer\Form\Type\Base
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Form\Type\Base;

use Faulancer\Form\Type\AbstractType;

/**
 * Class Date
 */
class Date extends AbstractType
{
    /** @var string */
    protected $inputType = 'input';

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

        $output .= '/>';

        if (!empty($this->definition['default'])) {

            $output = '<' . $this->inputType;
            $output .= ' type="hidden"';
            $output .= ' name="' . $this->definition['name'] . '"';
            $output .= ' ' . 'value="' . $this->definition['default'] . '"';
            $output .= '/>';

        }

        $this->element = $output;

        return $this;
    }
}