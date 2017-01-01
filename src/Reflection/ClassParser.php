<?php

namespace Faulancer\Reflection;

/**
 * Class ClassParser
 *
 * @package Faulancer\Reflection
 * @author  Florian Knapp <office@florianknapp.de>
 */
class ClassParser extends \ReflectionClass
{

    private $className;

    public function __construct($argument)
    {
        parent::__construct($argument);
        $this->className = $argument;
    }

    /**
     * @param string $name
     * @param string $method
     *
     * @return array
     */
    public function getMethodDoc($name = '', string $method = '')
    {
        $result   = [];

        foreach ($this->getMethods() as $func) {
            $result[$this->className][] = $this->extractValues($name, $func->name);
        }

        return $result;

    }

    /**
     * @param $name
     * @param $method
     *
     * @return bool
     */
    private function extractValues($name, $method)
    {
        $arr       = [];
        $methodDoc = new \ReflectionMethod($this->className, $method);

        preg_match('|@' . $name . '(.*)|', $methodDoc->getDocComment(), $arr);

        if (empty($arr)) {
            return false;
        }

        $var = [];

        preg_match('|\((.*)\)|', $arr[1], $var);

        $param = str_replace(
            ['"', ',', ' ', '+'],
            ['', '&', '', '___'],
            $var[1]
        );

        parse_str($param, $result);

        $result['action'] = $method;
        $result['class']  = $this->className;

        return $result;
    }

}