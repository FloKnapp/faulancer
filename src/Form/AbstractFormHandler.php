<?php
/**
 * Class AbstractFormHandler | AbstractFormHandler.php
 * @package Faulancer\Form
 */
namespace Faulancer\Form;

use Faulancer\Controller\Controller;
use Faulancer\Form\Validator\AbstractValidator;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class AbstractFormHandler
 */
abstract class AbstractFormHandler extends Controller
{

    /**
     * Holds the success url where can be redirect to after success
     * @var string
     */
    private $successUrl;

    /**
     * Holds the error url where can be redirect to after failed validation
     * @var string
     */
    private $errorUrl;

    /**
     * Return the called form handler
     *
     * @return self
     */
    protected function getForm() :self
    {
        return $this;
    }

    /**
     * @param string $field
     * @return string
     */
    protected function getFormData(string $field) :string
    {
        return $this->getRequest()->getPostData()[$field];
    }

    /**
     * Check data validity
     *
     * @return boolean
     */
    protected function isValid() :bool
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

        $this->getSessionManager()->setFlashbag('errors', $errors);

        return false;

    }

    /**
     * Validate with the defined validators
     *
     * @return array
     */
    private function validate() :array
    {
        $result = [];

        $this->getSessionManager()->setFlashbagFormData($this->getRequest()->getPostData());

        foreach ($this->getRequest()->getPostData() as $key => $data) {

            if (strpos($key, '/') === false) {
                continue;
            }

            $parts      = explode('/', $key);
            $validator  = ucfirst($parts[0]);
            $value      = $data;

            $validatorClass = '\Faulancer\Form\Validator\Type\\' . $validator;

            if (!class_exists($validatorClass)) {

                /** @var Config $config */
                $config         = ServiceLocator::instance()->get(Config::class);
                $nsPrefix       = $config->get('namespacePrefix');
                $validatorClass = str_replace('Faulancer', $nsPrefix, $validatorClass);

                if (!class_exists($validatorClass)) {
                    continue;
                }

            }

            /** @var AbstractValidator $val */
            $val     = new $validatorClass();
            $isValid = $val->process($value);

            $result[$key]['valid'] = $isValid;

            if (!$isValid) {
                $result[$key]['message'] = $val->getMessage();
            }

        }

        return $result;
    }

    /**
     * Returns the success url
     *
     * @return string
     */
    protected function getSuccessUrl() :string
    {
        return $this->successUrl;
    }

    /**
     * Set the success url
     *
     * @param string $successUrl
     */
    protected function setSuccessUrl(string $successUrl)
    {
        $this->successUrl = $successUrl;
    }

    /**
     * Return the error url
     *
     * @return string
     */
    protected function getErrorUrl() :string
    {
        return $this->errorUrl;
    }

    /**
     * Set the error url
     *
     * @param string $errorUrl
     */
    protected function setErrorUrl(string $errorUrl)
    {
        $this->errorUrl = $errorUrl;
    }

    /**
     * Init method which must be implemented
     *
     * @return mixed
     */
    abstract public function run();

}