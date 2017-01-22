<?php
/**
 * Class AbstractValidator | AbstractValidator.php
 * @package Faulancer\Form
 */
namespace Faulancer\Form\Validator;

/**
 * Class AbstractValidator
 */
abstract class AbstractValidator
{

    /**
     * Define the error message in case of validation fails
     * @var string
     */
    protected $errorMessage = '';

    /**
     * Return the error message
     * @return string
     */
    public function getMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Init method which must be implemented by every validator
     * @param mixed $data
     * @return mixed
     */
    public abstract function process($data);

}