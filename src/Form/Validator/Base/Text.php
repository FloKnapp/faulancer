<?php
/**
 * Class Text | Text.php
 * @package Faulancer\Form\Validator\Base
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Form\Validator\Base;

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
        if (empty($data)) {
            $this->errorMessage = 'validator_empty_text';
        }

        if (!preg_match('/^[a-z0-9\s\-_]+$/i', $data)) {
            $this->errorMessage = 'validator_invalid_text';
            return false;
        }

        return !empty($data) && is_string($data) && !is_numeric($data);
    }

}