<?php

namespace Faulancer\Form\Validator\Type;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class Image
 * @package Faulancer\Form\Validator\Type
 */
class Image extends AbstractValidator
{

    /** @var string */
    protected $errorMessage = 'validator_invalid_image_type';

    /** @var array */
    private $validImageMimeTypes = [
        'image/jpg',
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/tiff',
        'image/psd',
        'image/x-icon',
        'image/svg+xml'
    ];

    /**
     * @param string $data
     * @return boolean
     */
    public function process($data)
    {
        if (!file_exists($data)) {
            return false;
        }

        $data = mime_content_type($data);
        return in_array($data, $this->validImageMimeTypes);
    }

}