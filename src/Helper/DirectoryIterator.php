<?php

namespace Faulancer\Helper;

use Faulancer\Exception\ConstantMissingException;

/**
 * Class DirectoryIterator
 *
 * @package src\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
class DirectoryIterator
{

    private static $files = [];

    /**
     * @param string $dir
     *
     * @return array
     * @throws ConstantMissingException
     */
    public static function getFiles($dir = '')
    {
        if (!defined('PROJECT_ROOT') || !defined('NAMESPACE_ROOT')) {
            throw new ConstantMissingException('Constants PROJECT_ROOT and NAMESPACE_ROOT missing');
        }

        $dir = empty($dir) ? PROJECT_ROOT . '/' . lcfirst(NAMESPACE_ROOT) . '/Controller' : $dir;

        foreach (scandir($dir) as $res) {

            if ($res === '.' || $res === '..') {
                continue;
            }

            if (is_dir(realpath($dir . '/' . $res))) {
                self::getFiles($dir . '/' . $res);
                continue;
            }

            $namespace = ucfirst(str_replace([PROJECT_ROOT . '/', '/'], ['', '\\'], $dir));

            self::$files[$namespace][] = $res;

        }

        return self::$files;

    }

}