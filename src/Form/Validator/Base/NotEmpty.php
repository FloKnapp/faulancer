<?php
/**
 * Class NotEmpty | NotEmpty.php
 * @package Form\Validator\Base
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Form\Validator\Base;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class NotEmpty
 */
class NotEmpty extends AbstractValidator
{

    /** @var string  */
    protected $errorMessage = 'validator_empty_text';

    /**
     * @param mixed $data
     * @return bool
     * @codeCoverageIgnore
     */
    public function process($data)
    {
        return !empty($data);
    }

}