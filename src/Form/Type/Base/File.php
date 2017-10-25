<?php
/**
 * Class Text | Text.php
 * @package Faulancer\Form\Type\Base
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Form\Type\Base;

use Faulancer\Form\Type\AbstractType;

/**
 * Class Text
 */
class File extends AbstractType
{

    /** @var string */
    protected $inputType = 'input';

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

        $output .= '/>';

        $this->element = $output;

        return $this;
    }

}