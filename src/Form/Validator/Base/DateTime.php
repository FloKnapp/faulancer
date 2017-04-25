<?php
/**
 * Class DateTime | DateTime.php
 * @package Faulancer\Form\Validator\Base
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Form\Validator\Base;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class DateTime
 */
class DateTime extends AbstractValidator
{

    /**
     * The error message as key for translation
     * @var string
     */
    protected $errorMessage = 'validator_invalid_datetime_format';

    /**
     * Proof date validation with the \DateTime object
     * @param string $data
     * @return boolean
     */
    public function process($data)
    {
        try {
            $date = new \DateTime($data);
            return true;
        } catch (\Exception $e) {
            // We just validate the correct format with \DateTime and want a boolean return value
        }

        return false;
    }

}