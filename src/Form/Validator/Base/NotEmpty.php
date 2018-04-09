<?php

namespace Faulancer\Form\Validator\Base;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class NotEmpty
 *
 * @package Form\Validator\Base
 * @author  Florian Knapp <office@florianknapp.de>
 */
class NotEmpty extends AbstractValidator
{

    /** @var string  */
    protected $errorMessage = 'validator_empty_text';

    /**
     * @param mixed $data
     *
     * @return bool
     */
    public function process($data)
    {
        return !empty($data);
    }

}