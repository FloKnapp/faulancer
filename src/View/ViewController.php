<?php

namespace Faulancer\View;

use Faulancer\Exception\ConstantMissingException;
use Faulancer\Exception\FileNotFoundException;

class ViewController
{

    /** @var array */
    private $variable           = [];

    /** @var string */
    private $template           = "";

    /** @var ViewController */
    private $extendedTemplate   = null;

    /** @var array */
    private $assetStylesheets   = [];

    /** @var array */
    private $assetScripts       = [];

    /**
     * @param string $template
     * @throws FileNotFoundException
     * @return $this
     * @throws ConstantMissingException
     */
    public function setTemplate(string $template = '')
    {
        if (!defined('VIEWS_ROOT')) {
            throw new ConstantMissingException('Constant VIEWS_ROOT not defined');
        }

        if (empty($template)) {
            throw new FileNotFoundException('Template name missing');
        }

        $this->template = VIEWS_ROOT . '/' . $template;

        if (!file_exists($this->template)) {
            throw new FileNotFoundException('Template "' . $this->template . '" not found');
        }

        return $this;
    }

    /**
     * @param string $file
     * @return $this
     */
    public function setScripts(string $file)
    {
        $this->assetScripts[] = $file;
        return $this;
    }

    /**
     * @param string $file
     * @return $this
     */
    public function setStylesheets(string $file)
    {
        $this->assetStylesheets[] = $file;
        return $this;
    }

    public function setAssetsJs(array $assets)
    {
        $this->setVariable('assetsJs', $assets);
        return $this;
    }

    public function setAssetsCss(array $assets)
    {
        $this->setVariable('assetsCss', $assets);
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return (string) $this->template;
    }

    /**
     * @param string $key
     * @param string|array $value
     */
    public function setVariable(string $key = '', $value = null)
    {
        $this->variable[$key] = $value;
    }

    /**
     * @param $key
     * @return boolean|string|array
     */
    public function getVariable(string $key)
    {
        if(isset($this->variable[$key])) {
            return $this->variable[$key];
        }

        return false;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasVariable(string $key)
    {
        if(isset($this->variable[$key])) {
            return true;
        }

        return false;
    }

    /**
     * @param array $variables
     * @return ViewController $this
     */
    public function setVariables(array $variables = [])
    {
        foreach($variables AS $key=>$value) {
            $this->setVariable($key, $value);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getVariables()
    {
        return $this->variable;
    }

    /**
     * @param ViewController $view
     */
    public function setExtendedTemplate(ViewController $view)
    {
        $this->extendedTemplate = $view;
    }

    /**
     * @return ViewController
     */
    public function getExtendedTemplate()
    {
        return $this->extendedTemplate;
    }

    /**
     * @param $output
     * @return string
     */
    private function cleanOutput($output)
    {
        return str_replace(array("\t", "\r", "  "), "", trim($output));
    }

    /**
     * @return string
     */
    public function render()
    {
        /** @var GenericViewHelper $viewFunctions Expose View Functions to its Template*/
        $v = new GenericViewHelper($this);
        extract([$v]);
        extract($this->variable);

        ob_start();

        include $this->getTemplate();

        $content = ob_get_contents();

        ob_end_clean();

        if( $this->getExtendedTemplate() instanceof ViewController ) {
            return $this->cleanOutput($this->getExtendedTemplate()->setVariables($this->getVariables())->setAssetsJs($this->assetScripts)->setAssetsCss($this->assetStylesheets)->render());
        } else {
            return $this->cleanOutput($content);
        }
    }

    /**
     * Magic method for providing a view helper
     *
     * @param $name
     * @param $arguments
     * @return null
     * @throws FileNotFoundException
     */
    public function __call($name, $arguments)
    {

        $parts     = explode('\\', $name);
        $className = array_splice($parts, count($parts) - 1, 1);
        $classPath = implode('/' . $parts) . '/' . $className . '.php';
        $class     = new $className();

        if (file_exists($classPath) && method_exists($class, '__construct')) {
            return call_user_func_array([$class, '__construct'], $arguments);
        }

        return null;
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        unset( $this->variable );
        unset( $this->template );
    }

}