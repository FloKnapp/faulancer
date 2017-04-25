<?php
/**
 * Class AbstractValidator | AbstractValidator.php
 * @package Faulancer\Form
 */
namespace Faulancer\Form\Validator;

use Faulancer\Form\Type\AbstractType;

/**
 * Class AbstractValidator
 */
abstract class AbstractValidator
{

    protected $field;

    public function __construct(AbstractType $field)
    {
        $this->field = $field;
    }

    /**
     * Define the error message in case of validation fails
     * @var string
     */
    protected $errorMessage = '';

    /**
     * @return boolean
     */
    public function validate()
    {
        if (!$this->process($this->field->getValue())) {

            $this->field->setErrorMessage($this->getMessage());
            return false;

        }

        return true;
    }

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
    abstract public function process($data);

}