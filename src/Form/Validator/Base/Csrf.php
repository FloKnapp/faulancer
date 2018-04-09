<?php

namespace Faulancer\Form\Validator\Base;

use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class Csrf
 *
 * @package Faulancer\Form\Validator\Base
 * @author Florian Knapp <office@florianknapp.de>
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
     *
     * @param string $data
     * @return bool
     *
     * @throws ServiceNotFoundException
     */
    public function process($data)
    {
        return \Faulancer\Security\Csrf::isValid($data, $this->getField()->getFormIdentifier());
    }

}