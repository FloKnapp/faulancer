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
use Faulancer\Exception\Exception;
use Faulancer\Exception\FileNotFoundException;
use Faulancer\Exception\ViewHelperException;
use Faulancer\Exception\ViewHelperIncompatibleException;
use Faulancer\Service\Config;
use Faulancer\Service\ResponseService;
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
     * @var string
     */
    private $templatePath = '';

    /**
     * Holds the parent template
     * @var ViewController
     */
    private $parentTemplate = null;

    /**
     * Set template for this view
     *
     * @param string $template
     * @return ViewController
     * @throws ConstantMissingException
     * @throws FileNotFoundException
     */
    public function setTemplate(string $template = '') :self
    {
        /** @var Config $config */
        $config = ServiceLocator::instance()->get(Config::class);

        if (empty($this->templatePath) && strpos($template, $config->get('viewsRoot')) === false) {
            $template = $config->get('viewsRoot') . $template;
        } else {
            $template = $this->templatePath . $template;
        }

        if (empty($template) || !file_exists($template) || is_dir($template)) {
            throw new FileNotFoundException('Template "' . $template . '" not found');
        }

        $this->template = $template;

        return $this;
    }

    /**
     * @param string $path
     * @return ViewController
     */
    public function setTemplatePath(string $path = '') :self
    {
        $this->templatePath = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     * Add javascript from outside
     *
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
     *
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
     *
     * @return string
     */
    public function getTemplate() :string
    {
        return (string)$this->template;
    }

    /**
     * Set a single variable
     *
     * @param string $key
     * @param string|array $value
     */
    public function setVariable(string $key = '', $value = '')
    {
        $this->variable[$key] = $value;
    }

    /**
     * Get a single variable
     *
     * @param string $key
     * @return string|array
     */
    public function getVariable(string $key)
    {
        if(isset($this->variable[$key])) {
            return $this->variable[$key];
        }

        return '';
    }

    /**
     * Check if variable exists
     *
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
     *
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
     *
     * @return array
     */
    public function getVariables() :array
    {
        return $this->variable;
    }

    /**
     * Define parent template
     *
     * @param ViewController $view
     */
    public function setParentTemplate(ViewController $view)
    {
        $this->parentTemplate = $view;
    }

    /**
     * Get parent template
     *
     * @return ViewController
     */
    public function getParentTemplate()
    {
        return $this->parentTemplate;
    }

    /**
     * Strip spaces and tabs from output
     *
     * @param $output
     * @return string
     */
    private function cleanOutput($output) :string
    {
        return str_replace(array("\t", "\r", "  "), "", trim($output));
    }

    /**
     * Render the current view
     *
     * @return string
     */
    public function render()
    {
        extract($this->variable);

        ob_start();

        include $this->getTemplate();

        $content = ob_get_contents();

        ob_end_clean();

        if ($this->getParentTemplate() instanceof ViewController) {
            return $this->cleanOutput($this->getParentTemplate()->setVariables($this->getVariables())->render());
        } else {
            return $this->cleanOutput($content);
        }
    }

    /**
     * Magic method for providing a view helper
     *
     * @param  string $name      The class name
     * @param  array  $arguments Arguments if given
     * @return AbstractViewHelper
     * @throws ViewHelperException
     */
    public function __call($name, $arguments)
    {
        // Search in core view helpers first

        $coreViewHelper = __NAMESPACE__ . '\Helper\\' . ucfirst($name);

        if (class_exists($coreViewHelper)) {
            $class = new $coreViewHelper;
            array_unshift($arguments, $this);

            return call_user_func_array($class, $arguments);
        }

        // No core implementations found; search in custom view helpers

        /** @var Config $config */
        $config = ServiceLocator::instance()->get(Config::class);
        $namespace = '\\' . $config->get('namespacePrefix');

        $customViewHelper = $namespace . '\\View\\' . ucfirst($name);

        if (class_exists($customViewHelper)) {
            $class = new $customViewHelper;
            array_unshift($arguments, $this);

            return $class($arguments);
        }


        throw new ViewHelperException('No view helper for "' . $name . '" found.');
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