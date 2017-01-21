<?php
/**
 * Class ViewController | ViewController.php
 *
 * @package Faulancer\View
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View;

use Faulancer\Exception\ClassNotFoundException;
use Faulancer\Exception\ConstantMissingException;
use Faulancer\Exception\FileNotFoundException;
use Faulancer\Exception\ViewHelperIncompatibleException;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class ViewController
 */
class ViewController
{

    /**
     * Holds the view variables
     * @var array
     */
    private $variable = [];

    /**
     * Holds the view template
     * @var string
     */
    private $template = '';

    /**
     * Holds the parent template
     * @var ViewController
     */
    private $extendedTemplate = null;

    /**
     * Set template for this view
     * @param string $template
     * @return self
     * @throws ConstantMissingException
     * @throws FileNotFoundException
     */
    public function setTemplate(string $template = '') :self
    {
        /** @var Config $config */
        $config = ServiceLocator::instance()->get(Config::class);

        if (strpos($template, $config->get('viewsRoot')) === false) {
            $template = $config->get('viewsRoot') . $template;
        }

        if (empty($template) || !file_exists($template) || is_dir($template)) {
            throw new FileNotFoundException('Template "' . $template . '" not found');
        }

        $this->template = $template;

        return $this;
    }

    /**
     * Add javascript from outside
     * @param string $file
     * @return self
     */
    public function addScript($file) :self
    {
        $this->variable['assetsJs'][] = $file;
        return $this;
    }

    /**
     * Add stylesheet from outside
     * @param string $file
     * @return self
     */
    public function addStylesheet($file) :self
    {
        $this->variable['assetsCss'][] = $file;
        return $this;
    }

    /**
     * Return current template
     * @return string
     */
    public function getTemplate() :string
    {
        return (string)$this->template;
    }

    /**
     * Set a single variable
     * @param string $key
     * @param string|array $value
     */
    public function setVariable(string $key = '', $value = '')
    {
        $this->variable[$key] = $value;
    }

    /**
     * Get a single variable
     * @param $key
     * @return string|array
     */
    public function getVariable($key)
    {
        if(isset($this->variable[$key])) {
            return $this->variable[$key];
        }

        return '';
    }

    /**
     * Check if variable exists
     * @param string $key
     * @return bool
     */
    public function hasVariable(string $key) :bool
    {
        if(isset($this->variable[$key])) {
            return true;
        }

        return false;
    }

    /**
     * Set many variables at once
     * @param array $variables
     * @return self
     */
    public function setVariables(array $variables = []) :self
    {
        foreach($variables AS $key=>$value) {
            $this->setVariable($key, $value);
        }

        return $this;
    }

    /**
     * Get all variables
     * @return array
     */
    public function getVariables() :array
    {
        return $this->variable;
    }

    /**
     * Define parent template
     * @param ViewController $view
     */
    public function setExtendedTemplate(ViewController $view)
    {
        $this->extendedTemplate = $view;
    }

    /**
     * Get parent template
     * @return ViewController
     */
    public function getExtendedTemplate()
    {
        return $this->extendedTemplate;
    }

    /**
     * Strip spaces and tabs from output
     * @param $output
     * @return string
     */
    private function cleanOutput($output) :string
    {
        return str_replace(array("\t", "\r", "  "), "", trim($output));
    }

    /**
     * Render the current view
     * @return string
     */
    public function render() :string
    {
        /** @var GenericViewHelper $viewFunctions Expose View Functions to its Template*/
        $v = new GenericViewHelper($this);
        extract([&$v]);
        extract($this->variable);

        ob_start();

        include $this->getTemplate();

        $content = ob_get_contents();

        ob_end_clean();

        if( $this->getExtendedTemplate() instanceof ViewController ) {
            return $this->cleanOutput($this->getExtendedTemplate()->setVariables($this->getVariables())->render());
        } else {
            return $this->cleanOutput($content);
        }
    }

    /**
     * Magic method for providing a view helper
     * @param $name
     * @param $arguments
     * @return AbstractViewHelper
     * @throws FileNotFoundException
     * @throws ViewHelperIncompatibleException
     * @throws ClassNotFoundException
     */
    public function __call($name, $arguments) :AbstractViewHelper
    {
        /** @var Config $config */
        $config = ServiceLocator::instance()->get(Config::class);

        $className = $name;
        $namespace = '\\' . $config->get('namespacePrefix');

        $className = $namespace . '\\View\\' . $className;

        if (!class_exists($className)) {
            throw new ClassNotFoundException('ViewHelper ' . $name . ' couldn\'t be found');
        }

        if (method_exists($className, '__construct')) {
            $ref = new \ReflectionClass($className);
            /** @var AbstractViewHelper $class */
            $class = $ref->newInstanceArgs($arguments);
            return $class;
        }

        if (method_exists($className, '__toString')) {
            return new $className();
        }

        throw new ViewHelperIncompatibleException('No compatible methods found');
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