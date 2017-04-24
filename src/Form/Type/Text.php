<?php
/**
 * Class Text
 * @package Faulancer\Form\Type
 */
namespace Faulancer\Form\Type;

/**
 * Class Text
 */
class Text extends AbstractType
{

    public function build(array $definition)
    {

        $pattern = '<input type="%s" name="%s" />';

        $result = sprintf($pattern, $definition['type'], $definition['name']);

        foreach ($definition['attributes'] as $attr => $value) {



        }

    }

}