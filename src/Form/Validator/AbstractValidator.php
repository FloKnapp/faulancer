<?php
/**
 * Class AbstractValidator | AbstractValidator.php
 * @package Faulancer\Form\Validator
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Form\Validator;

use Faulancer\Form\Type\AbstractType;
use Faulancer\Translate\Translator;

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
     * @codeCoverageIgnore
     */
    public function validate()
    {
        if (!$this->process($this->field->getValue())) {

            $this->field->setErrorMessages([$this->getMessage()]);
            return false;

        }

        return true;
    }

    /**
     * @return AbstractType
     * @codeCoverageIgnore
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Return the error message
     * @return string
     * @codeCoverageIgnore
     */
    public function getMessage()
    {
        $translate = new Translator();
        return $translate->translate($this->errorMessage);
    }

    /**
     * Init method which must be implemented by every validator
     * @param mixed $data
     * @return mixed
     */
    abstract public function process($data);

}