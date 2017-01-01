<?php

namespace Form\Handler;

use Faulancer\Exception\SecurityException;
use Faulancer\Form\Validator\AbstractValidator;
use Faulancer\Http\Request;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\Session\SessionManager;
use Faulancer\Translate\Translator;

/**
 * Class AbstractFormHandler
 *
 * @package Faulancer\Form\Handler
 * @author Florian Knapp <office@florianknapp.de>
 */
abstract class AbstractFormHandler
{

    /** @var array */
    protected $config;

    /** @var string */
    protected $successUrl;

    /** @var string */
    protected $errorUrl;

    /** @var Request $request */
    protected $request;

    /** @var AbstractValidator $validator */
    protected $validator;

    /**
     * AbstractFormHandler constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->i18n = new Translator();
    }

    /**
     * @param AbstractValidator|null $validator
     * @throws SecurityException
     * @return bool
     */
    public function validate($validator)
    {
        $errors   = [];
        $formData = $this->getFormData();

        $this->successUrl = isset($formData['successUrl']) ? $formData['successUrl'] : null;
        $this->errorUrl   = isset($formData['errorUrl']) ? $formData['errorUrl'] : null;

        if (isset($formData['csrf'])) {
            $tokenFromSession = SessionManager::instance()->getFlashbag('csrf');
            if ($formData['csrf'] !== $tokenFromSession) {
                throw new SecurityException('A non valid CSRF-Token was found in request');
            }
        }

        if ($validator === null) {
            return true;
        }

        foreach ($validator->validationOptions() AS $field => $validation) {

            foreach ($validation AS $option) {

                switch ($option) {

                    case 'not_empty':
                        if (empty($formData[$field]))
                            $errors[$field][$option] = $this->i18n->translate('field_must_not_be_empty');
                        break;

                    case 'numeric':
                        if (!is_numeric($formData[$field]))
                            $errors[$field][$option] = $this->i18n->translate('field_must_contain_number');
                        break;

                    case 'is_string':
                        if (!is_string($formData[$field]))
                            $errors[$field][$option] = $this->i18n->translate('field_must_contain_chars');
                        break;

                    case 'is_email':
                        if (!filter_var($formData[$field], FILTER_VALIDATE_EMAIL))
                            $errors[$field][$option] = $this->i18n->translate('field_must_contain_valid_email');
                        break;

                    case 'same':

                        $field1 = $formData[$field];
                        $field2 = null;
                        $field2_name = null;

                        foreach ($formData as $item => $value) {
                            if (strpos($item, '_2') !== false) {
                                $field2_name = $item;
                                $field2 = $value;
                            }
                        }

                        if ($field1 !== $field2) {
                            $errors[$field][$option] = 'Die Eingaben stimmen nicht überein';
                            $errors[$field2_name][$option] = 'Die Eingaben stimmen nicht überein';
                        }

                        break;

                }

            }

        }

        if (count($errors)) {
            SessionManager::instance()->setFlashbagFormData($formData);
            SessionManager::instance()->setFlashbag(['errors' => $errors]);
            return false;
        }

        return true;

    }

    /**
     * @return array
     */
    protected function getFormData()
    {
        return $this->request->getPostData();
    }

    /**
     * @return ServiceLocator
     */
    protected function getServiceLocator()
    {
        return ServiceLocator::instance();
    }

    /**
     * @return mixed
     */
    abstract public function run();

}