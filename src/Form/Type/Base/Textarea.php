<?php
/**
 * Class Textarea | Textarea.php
 * @package Faulancer\Form\Type\Base
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Form\Type\Base;

use Faulancer\Form\Type\AbstractType;

/**
 * Class Textarea
 */
class Textarea extends AbstractType
{

    /** @var string */
    protected $inputType = 'textarea';

    /** @var string */
    protected $element = '';

    /**
     * @return self
     * @codeCoverageIgnore
     */
    public function create()
    {
        parent::create();

        $output = '<' . $this->inputType . ' ';

        foreach ($this->definition['attributes'] as $attr => $value) {

            if (!empty($this->getValue()) && $attr === 'value') {
                continue;
            }

            $output .= $attr . '="' . $value . '" ';

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