<?php

namespace Faulancer\View;

use Faulancer\Security\Csrf;
use Faulancer\Session\SessionManager;

/**
 * Class GenericViewHelper
 *
 * @package Faulancer\View
 * @author Florian Knapp <office@florianknapp.de>
 */
class GenericViewHelper
{

    /** @var ViewController $view */
    private $view;

    /** @var string $blockName */
    private $blockName;

    /** @var string $blockContent */
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
     * @param string $template
     */
    public function extendsTemplate(string $template)
    {
        $view = new ViewController();
        $view->setTemplate($template);
        $this->view->setExtendedTemplate($view);
    }

    /**
     * @param string $name
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
     * @param string $name
     * @param string $default
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
     * @param string $string
     * @return string
     */
    public function escape(string $string)
    {
        return stripslashes( strip_tags( $string ) );
    }

    /**
     * @param $type
     * @return string
     */
    public function getAssets($type)
    {
        $result = null;

        switch ($type) {

            case 'js':
                $pattern = '<script src="%s"></script>';
                break;

            case 'css':
                $pattern = '<link rel="stylesheet" type="text/css" href="%s">';
                break;

            default:
                $pattern = null;
                break;

        }

        $files = $this->view->getVariable('assets'.ucfirst($type));

        foreach ($files AS $file) {
            $result .= sprintf($pattern, $file). "\n";
        }

        return $result;
    }

    /**
     * @return string
     */
    public function generateCsrfToken()
    {
        return Csrf::getToken();
    }

    /**
     * @param $field
     * @return bool|string
     */
    public function getFormError($field)
    {
        $errors = SessionManager::instance()->getFlashbagError($field);

        if (isset($errors)) {
            return '<div class="form-error ' . $field . '"><span>' . implode('</span><span>', $errors) . '</span></div>';
        }

        return false;
    }

    /**
     * @param $field
     * @return boolean
     */
    public function hasFormError($field)
    {
        return SessionManager::instance()->hasFlashbagErrorsKey($field);
    }

    /**
     * @param $key
     * @return array|null|string
     */
    public function getFormData($key)
    {
        return SessionManager::instance()->getFlashbagFormData($key);
    }

    /**
     * @param $routeName
     * @param $parameter
     * @param bool $absolute
     * @return string|boolean
     */
    /*
    public function url($routeName, $parameter = [], $absolute = false)
    {
        $config = Config::get('routes');

        if (isset($config[$routeName]) || $routeName === null) {

            if ($routeName === null) {
                $url = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
            } else {
                $url = $config[$routeName]['route'];
            }

            if ($absolute === true) {
                $url = HOSTNAME . $url;
            }

            if (is_array($parameter) && !empty($parameter)) {

                if (strpos($parameter[0], '=') !== false) {
                    return $url . '?' . $parameter[0];
                }

                $params = implode('/', $parameter);
                return $url . '/' . $params;
            }

            return $config[$routeName]['route'];
        }

        return false;
    }


    public function translate($string, $value = null)
    {
        $translator = new Translator();
        return $translator->translate($string, $value);
    }

    */

}