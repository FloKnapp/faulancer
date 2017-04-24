<?php
/**
 * Class Confirm
 * @package Form\Validator\Type
 */
namespace Faulancer\Form\Validator\Type;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class Confirm
 */
class Confirm extends AbstractValidator
{

    /** @var string */
    protected $errorMessage = 'values_not_matching';

    /** @var null */
    protected static $initial = null;

    /**
     * @param string $data
     * @return bool
     */
    public function process($data)
    {
        if (self::$initial === null) {
            self::$initial = $data;
        } else {
            return self::$initial === $data;
        }

        return false;
    }

}