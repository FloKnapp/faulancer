<?php

namespace Faulancer\Form;

use Faulancer\Form\Validator\AbstractValidator;
use Faulancer\Http\Request;
use Faulancer\Session\SessionManager;

/**
 * Class FormHandler
 * @package Faulancer\Form
 */
abstract class AbstractFormHandler
{

    /** @var Request */
    private $request;

    /** @var string */
    private $successUrl;

    /** @var string */
    private $errorUrl;

    /**
     * AbstractFormHandler constructor.
     *
     * @param Request        $request
     * @param SessionManager $sessionManager
     */
    public function __construct(Request $request, SessionManager $sessionManager)
    {
        $this->request        = $request;
        $this->sessionManager = $sessionManager;
    }

    /**
     * @return self
     */
    protected function getForm()
    {
        return $this;
    }

    /**
     * @return boolean
     */
    protected function isValid()
    {
        $result = $this->validate();
        $errors = [];

        foreach ($result as $field => $data) {

            if ($data['valid']) {
                continue;
            }

            $errors[$field][] = [
                'message' => $data['message']
            ];

        }

        if (empty($errors)) {
            return true;
        }

        $this->sessionManager->setFlashbag('errors', $errors);

        return false;

    }

    /**
     * @return array
     */
    private function validate()
    {
        $result = [];

        foreach ($this->request->getPostData() as $key => $data) {

            if (strpos($key, '/') === false) {
                continue;
            }

            $parts      = explode('/', $key);
            $validator  = ucfirst($parts[0]);
            $name       = $parts[1];
            $value      = $data;

            $validatorClass = '\Faulancer\Form\Validator\Type\\' . $validator;

            if (!class_exists($validatorClass)) {
                continue;
            }

            /** @var AbstractValidator $val */
            $val = new $validatorClass();

            $result[$name] = [
                'valid'   => $val->process($value),
                'message' => $val->getMessage()
            ];

        }

        return $result;

    }

    /**
     * @return string
     */
    protected function getSuccessUrl()
    {
        return $this->successUrl;
    }

    /**
     * @param string $successUrl
     */
    protected function setSuccessUrl(string $successUrl)
    {
        $this->successUrl = $successUrl;
    }

    /**
     * @return string
     */
    protected function getErrorUrl()
    {
        return $this->errorUrl;
    }

    /**
     * @param string $errorUrl
     */
    protected function setErrorUrl(string $errorUrl)
    {
        $this->errorUrl = $errorUrl;
    }

    protected abstract function run();
    
}