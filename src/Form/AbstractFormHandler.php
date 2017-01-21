<?php
/**
 * Class AbstractFormHandler | AbstractFormHandler.php
 * @package Faulancer\Form
 */
namespace Faulancer\Form;

use Faulancer\Form\Validator\AbstractValidator;
use Faulancer\Http\Request;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\Session\SessionManager;

/**
 * Class AbstractFormHandler
 */
abstract class AbstractFormHandler
{

    /**
     * Holds the request object
     * @var Request
     */
    private $request;

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
     * Holds the session manager
     * @var SessionManager
     */
    private $sessionManager;

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
     * Return the called form handler
     * @return self
     */
    protected function getForm() :self
    {
        return $this;
    }

    /**
     * Check data validity
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

        $this->sessionManager->setFlashbag('errors', $errors);

        return false;

    }

    /**
     * Validate with the defined validators
     * @return array
     */
    private function validate() :array
    {
        $result = [];

        $this->sessionManager->setFlashbagFormData($this->request->getPostData());

        foreach ($this->request->getPostData() as $key => $data) {

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
     * @return string
     */
    protected function getSuccessUrl() :string
    {
        return $this->successUrl;
    }

    /**
     * Set the success url
     * @param string $successUrl
     */
    protected function setSuccessUrl(string $successUrl)
    {
        $this->successUrl = $successUrl;
    }

    /**
     * Return the error url
     * @return string
     */
    protected function getErrorUrl() :string
    {
        return $this->errorUrl;
    }

    /**
     * Set the error url
     * @param string $errorUrl
     */
    protected function setErrorUrl(string $errorUrl)
    {
        $this->errorUrl = $errorUrl;
    }

    /**
     * Init method which must be implemented
     * @return mixed
     */
    abstract public function run();
    
}