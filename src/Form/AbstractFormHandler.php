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

    private $isConfirm = false;

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
    public function getFormData(string $field) :string
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
        $result   = [];
        $postData = $this->getRequest()->getPostData();

        $this->getSessionManager()->setFlashbagFormData($postData);

        foreach ($postData as $key => $data) {

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

            if ($validator === 'Confirm' && $this->isConfirm === false) {
                $this->isConfirm = true;
                continue;
            }

            $result[$key]['valid'] = $isValid;

            if (!$isValid) {
                $result[$key]['message'] = $val->getMessage();
            }

            if ($key === 'csrf') {

                $token = $this->getSessionManager()->getFlashbag('csrf');

                if ($token !== $value) {
                    $result['csrf']['valid']   = false;
                    $result['csrf']['message'] = 'invalid_csrf_token';
                }

            }

        }

        return $result;
    }

    /**
     * Init method which must be implemented
     *
     * @return mixed
     */
    abstract public function run();

}