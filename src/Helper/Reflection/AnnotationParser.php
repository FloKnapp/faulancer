<?php
/**
 * Class ClassParser
 *
 * @package Faulancer\Helper\Reflection
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Helper\Reflection;

/**
 * Class AnnotationParser
 */
class AnnotationParser extends \ReflectionClass
{

    /**
     * The class name which should be parsed
     * @var string
     */
    private $className;

    /**
     * AnnotationParser constructor.
     * @param mixed $argument
     */
    public function __construct($argument)
    {
        parent::__construct($argument);
        $this->className = $argument;
    }

    /**
     * Get class methods
     * @param string $name The name of the annotation
     * @return array
     */
    public function getMethodDoc($name = '')
    {
        $result = [];

        foreach ($this->getMethods() as $func) {

            if ('\\' . $func->class === $this->className) {
                $result[$this->className][] = $this->extractValues($name, $func->name);
            }
 
        }

        return $result;
    }

    /**
     * Extract values from annotation
     * @param string $name   The annotations name
     * @param string $method The method name
     * @return array
     */
    private function extractValues($name, $method)
    {
        $arr       = [];
        $vars      = [];
        $methodDoc = new \ReflectionMethod($this->className, $method);

        preg_match('|@' . $name . '(.*)|', $methodDoc->getDocComment(), $arr);

        if (empty($arr)) {
            return [];
        }

        preg_match('|\((.*)\)|', $arr[1], $vars);

        $param = str_replace(
            ['"', ',', ' ', '+'],
            ['', '&', '', '___'],
            $vars[1]
        );

        // Parse uri conform string into key/value pairs
        parse_str($param, $result);

        // Add class and action to result variable
        $result['action'] = $method;
        $result['class']  = $this->className;

        return $result;
    }

}
