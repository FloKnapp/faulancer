<?php
/**
 * Class Text
 * @package Faulancer\Form\Validator\Type
 */
namespace Faulancer\Form\Validator\Type;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class Text
 */
class Text extends AbstractValidator
{

    /**
     * The error message as key for translation
     * @var string
     */
    protected $errorMessage = 'validator_invalid_text';

    /**
     * Validate type string
     * @param $data
     * @return boolean
     */
    public function process($data)
    {
        return !empty($data) && is_string($data) && !is_numeric($data);
    }

}