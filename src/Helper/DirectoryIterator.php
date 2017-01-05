<?php

namespace Faulancer\Helper;

use Faulancer\Exception\ConstantMissingException;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class DirectoryIterator
 *
 * @package src\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
class DirectoryIterator
{

    private static $files = [];

    private static $defaultDirectory = '/Controller';

    /**
     * @param string $dir
     *
     * @return array
     * @throws ConstantMissingException
     */
    public static function getFiles($dir = '')
    {
        /** @var Config $config */
        $config = ServiceLocator::instance()->get(Config::class);

        $dir = empty($dir) ? $config->get('applicationRoot') . self::$defaultDirectory : $dir;

        foreach (scandir($dir) as $res) {

            if ($res === '.' || $res === '..') {
                continue;
            }

            if (is_dir($dir . '/' . $res)) {
                self::getFiles($dir . '/' . $res);
                continue;
            }

            $namespace = ucfirst(str_replace([$config->get('applicationRoot'), '/'], ['', '\\'], $dir));

            if (!empty($config->get('namespacePrefix'))) {
                $namespace = $config->get('namespacePrefix') . $namespace;
            }

            self::$files[$namespace][] = $res;

        }

        return self::$files;

    }

}