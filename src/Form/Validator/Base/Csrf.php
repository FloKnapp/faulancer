<?php
/**
 * Class Csrf | Csrf.php
 * @package Faulancer\Form\Validator\Base
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Form\Validator\Base;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class Email
 */
class Csrf extends AbstractValidator
{

    /**
     * The error message as key for translation
     * @var string
     */
    protected $errorMessage = 'validator_invalid_token';

    /**
     * Validate email with filter_var
     * @param $data
     * @return boolean
     */
    public function process($data)
    {
        return \Faulancer\Security\Csrf::isValid($data, $this->getField()->getFormIdentifier());
    }

}