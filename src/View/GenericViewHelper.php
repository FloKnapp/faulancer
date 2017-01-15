<?php
/**
 * Class GenericViewHelper
 *
 * @package Faulancer\View
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View;

use Faulancer\Security\Csrf;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\Session\SessionManager;
use Faulancer\Translate\Translator;

/**
 * Class GenericViewHelper
 */
class GenericViewHelper
{

    /**
     * Instance of view controller
     * @var ViewController $view
     */
    private $view;

    /**
     * The block name
     * @var string $blockName
     */
    private $blockName;

    /**
     * The block content
     * @var string $blockContent
     */
    private $blockContent;

    /**
     * ViewFunctions constructor.
     * @param ViewController $view
     */
    public function __construct(ViewController $view)
    {
        $this->view = $view;
    }

    /**
     * Render view
     * @param string $template
     * @param array $variables
     * @return string
     */
    public function render(string $template = "", array $variables = [])
    {
        $view = new ViewController();
        $view->setTemplate( $template );
        $view->setVariables( $variables );
        return $view->render();
    }

    /**
     * Set parent template
     * @param string $template
     */
    public function extendsTemplate(string $template)
    {
        $view = new ViewController();
        $view->setTemplate($template);
        $this->view->setExtendedTemplate($view);
    }

    /**
     * Set block
     * @param string $name The block name
     */
    public function block(string $name = "")
    {

        if (!empty($name)) {

            $this->blockName = $name;
            ob_start();

        } else {

            $this->blockContent = ob_get_flush();
            $this->view->getExtendedTemplate()->setVariable($this->blockName, $this->blockContent);
            unset($this->blockName);

        }

    }

    /**
     * Return block contents
     * @param string $name    The block name
     * @param string $default The default value
     * @return string
     */
    public function renderBlock(string $name, string $default = "")
    {
        if($this->view->getVariable($name) == null) {
            return $default;
        }
        return trim($this->view->getVariable( $name ));
    }

    /**
     * Strip slashes from value
     * @param string $string
     * @return string
     */
    public function escape(string $string)
    {
        return stripslashes( strip_tags( $string ) );
    }

    /**
     * Get assets by type
     * @param $type
     * @return string
     */
    public function getAssets($type)
    {
        $result  = '';
        $pattern = '';

        switch ($type) {

            case 'js':
                $pattern = '<script src="%s"></script>';
                break;

            case 'css':
                $pattern = '<link rel="stylesheet" type="text/css" href="%s">';
                break;

        }

        $files = $this->view->getVariable('assets'.ucfirst($type));

        foreach ($files AS $file) {
            $result .= sprintf($pattern, $file). "\n";
        }

        return $result;
    }

    /**
     * Generate a CSRF token
     * @return string
     */
    public function generateCsrfToken()
    {
        return Csrf::getToken();
    }

    /**
     * Get form error by input field
     * @param $field
     * @return bool|string
     */
    public function getFormError($field)
    {
        $error = SessionManager::instance()->getFlashbagError($field);

        if (!empty($error)) {

            $result = '<div class="form-error ' . $field . '">';

            foreach ($error as $err) {
                $result .= '<span>' . $this->translate($err['message']) . '</span>';
            }

            $result .= '</div>';

            return $result;

        }

        return false;
    }

    /**
     * Check if form error exists
     * @param $field
     * @return boolean
     */
    public function hasFormError($field)
    {
        return SessionManager::instance()->hasFlashbagErrorsKey($field);
    }

    /**
     * Get form data for specific input field
     * @param $key
     * @return array|null|string
     */
    public function getFormData($key)
    {
        return SessionManager::instance()->getFlashbagFormData($key);
    }

    /**
     * @param string $name
     * @return string
     * @throws \Exception
     */
    public function route($name)
    {
        /** @var Config $config */
        $config = ServiceLocator::instance()->get(Config::class);
        $routes = require $config->get('routeFile');

        foreach ($routes as $routeName => $data) {

            if ($routeName === $name) {
                return $data['path'];
            }

        }

        throw new \Exception('No route for name "' . $name . '" found');
    }

    /**
     * Translate key
     * @param string $string The key which holds the translated value
     * @param array  $value  The variable content for the placeholder
     * @return string
     */
    public function translate($string, $value = [])
    {
        $translator = new Translator();
        return $translator->translate($string, $value);
    }

}