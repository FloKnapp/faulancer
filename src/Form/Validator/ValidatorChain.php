<?php
/**
 * Class ValidatorChain | ValidatorChain.php
 * @package Form\Validator
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Form\Validator;

use Faulancer\Form\Type\AbstractType;

/**
 * Class ValidatorChain
 */
class ValidatorChain
{

    /** @var AbstractValidator[] */
    protected $validators = [];

    protected $messages = [];

    /** @var AbstractType|null */
    protected $field = null;

    public function __construct(AbstractType $field)
    {
        $this->field = $field;
    }

    /**
     * @param AbstractValidator $validator
     */
    public function add(AbstractValidator $validator)
    {
        $this->validators[] = $validator;
    }

    /**
     * @return boolean
     */
    public function validate() :bool
    {
        foreach ($this->validators as $validator) {

            $result = $validator->process($this->field->getValue());

            if (!$result) {
                $this->messages[] = $validator->getMessage();
            }

        }

        if (!empty($this->messages)) {
            $this->validators[0]->getField()->setErrorMessages($this->messages);
            return false;
        }

        return true;
    }

}