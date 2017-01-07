<?php

namespace Faulancer\Helper\Reflection;

/**
 * Class ClassParser
 *
 * @package Faulancer\Reflection
 * @author  Florian Knapp <office@florianknapp.de>
 */
class AnnotationParser extends \ReflectionClass
{

    /** @var string */
    private $className;

    public function __construct($argument)
    {
        parent::__construct($argument);
        $this->className = $argument;
    }

    /**
     * @param string $name
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
     * @param $name
     * @param $method
     *
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
