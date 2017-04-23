<?php
/**
 * Class ImageEntity
 * @package Faulancer\Form\Validator\Type
 */
namespace Faulancer\Form\Validator\Type;

use Faulancer\Form\Validator\AbstractValidator;

/**
 * Class ImageEntity
 */
class Image extends AbstractValidator
{

    /**
     * The error message as key for translation
     * @var string
     */
    protected $errorMessage = 'validator_invalid_image_type';

    /**
     * Valid image mime-types
     * @var array
     */
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
     * Validate image type within it's mime-type
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