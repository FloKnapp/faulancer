<?php

namespace Faulancer\Form\Validator\Base;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class Confirm
 *
 * @package Faulancer\Form\Validator\Base
 * @author Florian Knapp <office@florianknapp.de>
 */
class Confirm extends AbstractValidator
{

    static private $initialInput = null;

    /**
     * The error message as key for translation
     * @var string
     */
    protected $errorMessage = 'validator_not_same';

    /**
     * Validate type string
     *
     * @param $data
     *
     * @return bool
     */
    public function process($data)
    {
        if (empty(self::$initialInput)) {
            self::$initialInput = $data;
            return true;
        } elseif (!empty(self::$initialInput) && self::$initialInput === $data) {
            return true;
        }

        self::$initialInput = null;
        return false;
    }

}