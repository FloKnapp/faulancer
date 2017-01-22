<?php
/**
 * Class DirectoryIterator
 *
 * @package Faulancer\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Helper;

use Faulancer\Exception\ConstantMissingException;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class DirectoryIterator
 */
class DirectoryIterator
{

    /**
     * The directories files
     * @var array
     */
    private static $files = [];

    /**
     * The initial directory
     * @var string
     */
    private static $defaultDirectory = '/Controller';

    /**
     * Get files from directory
     *
     * @param string $dir
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