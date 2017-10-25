<?php
/**
 * Class Highlighter | Highlighter.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class Highlighter
 */
class Highlighter extends AbstractViewHelper
{
    
    private static $keys = [
        '\bif',
        'else',
        'new',
        'throw',
        'echo',
        'return',
        'null',
        'public',
        'private',
        'protected',
        'function',
        'class[?!class\=|?!\$class]',
        'extends',
        'implements',
        'use',
        'namespace',
        'unset',
        'include_once',
        'require_once',
        'include',
        'require',
        'foreach',
        'for',
        'try',
        'catch',
        '\bas\b',
        '&lt;\?php',
        '&lt;\?=',
        '\?&gt;\n'
    ];

    /**
     * @param ViewController $view
     * @param string         $data
     * @param string         $language
     * @return mixed|string
     * @codeCoverageIgnore
     */
    public function __invoke(ViewController $view, $data = '', $language = 'php')
    {
        $data = htmlentities($data, ENT_IGNORE, null, false);
        $data = preg_replace('/(' . implode('\b|\b', self::$keys) . ')/', '<span class="base_keys">$1</span>', $data);
        $data = preg_replace('/\$(\w+)/', '<span class="variable">$$1</span>', $data);
        $data = preg_replace('/\'(.*)\'/', '<span class="string_text">\'$1\'</span>', $data);
        $data = preg_replace('/-&gt;(\w+)/', '-><span class="methods">$1</span>', $data);
        $data = preg_replace('/\/\*\*(.*)\*\//s', '<span class="annotation">/**$1*/</span>', $data);
        $data = preg_replace('/(_*)([A-Z]{2,})(_*)/', '<span class="constant">$1$2$3</span>', $data);

        return $data;
    }

}