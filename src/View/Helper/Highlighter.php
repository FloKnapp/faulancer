<?php
/**
 * Class Highlighter | Highlighter.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\Exception\MethodNotFoundException;
use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class Highlighter
 */
class Highlighter extends AbstractViewHelper
{
    
    private static $keys = [
        'if',
        'else',
        'new',
        'throw',
        'return',
        'public',
        'private',
        'protected',
        'function',
        'class[?!class=][?!$class]',
        'extends',
        'implements',
        'use[^a-zA-Z]',
        'namespace',
        'unset',
        'include[^_once]',
        'require[^_once]',
        'include_once',
        'require_once',
        '&lt;\?php',
        '&lt;\?=',
        '\?&gt;'
    ];

    public function __invoke(ViewController $view, $data = '', $language = 'php')
    {
        $data = htmlentities($data, ENT_IGNORE, null, false);
        $data = preg_replace('/(' . implode('|', self::$keys) . ')/', '<span class="base_keys">$1</span>', $data);
        $data = preg_replace('/\$(\w+)/', '<span class="variable">$$1</span>', $data);
        $data = preg_replace('/\'(.*)\'/', '<span class="string_text">\'$1\'</span>', $data);
        $data = preg_replace('/-&gt;(\w+)/', '-><span class="methods">$1</span>', $data);

        return $data;
    }

}