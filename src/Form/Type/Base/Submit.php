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
    protected $type = 'input';

    /** @var string */
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