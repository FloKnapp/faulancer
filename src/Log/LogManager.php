<?php

namespace Faulancer\Log;

use Faulancer\Exception\ConfigInvalidException;
use Faulancer\Exception\LogFileInvalidException;
use Faulancer\Exception\LogTypeNotSupportedException;
use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\Service\Config;
use Faulancer\Log\Writer\DefaultWriter;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class Logger | Logger.php
 *
 * @package Faulancer\Log
 * @author  Florian Knapp <office@florianknapp.de>
 */
class LogManager
{

    const LEVEL_INFO    = 'info';
    const LEVEL_DEBUG   = 'debug';
    const LEVEL_WARN    = 'warn';
    const LEVEL_ERROR   = 'error';

    /** @var static */
    private static $instance;

    /** @var ServiceLocator */
    private static $serviceLocator;

    /** @var array */
    protected static $levels = [
        self::LEVEL_INFO,
        self::LEVEL_DEBUG,
        self::LEVEL_WARN,
        self::LEVEL_ERROR
    ];

    /** @var bool */
    protected static $fileSystemChecked = false;

    /** @var array */
    protected static $logFiles = [];

    /**
     * @return static
     */
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance       = new static();
            self::$serviceLocator = ServiceLocator::instance();
        }

        return self::$instance;
    }

    /**
     * Write to resource
     *
     * @param string $message
     * @param string $level
     * @param string $writer
     *
     * @return bool
     *
     * @throws ServiceNotFoundException
     * @throws LogTypeNotSupportedException
     */
    public static function log(string $message = '', string $level = self::LEVEL_INFO, string $writer = '')
    {
        if (!self::$fileSystemChecked) {
            self::_checkConsistency();
        }

        /** @var Config $config */
        $config = self::$serviceLocator->get(Config::class);

        if (!empty($writer) && class_exists($writer)) {
            $writer = new $writer($config, debug_backtrace());
        } else {
            $writer = new DefaultWriter($config, debug_backtrace());
        }

        return $writer->write(self::$logFiles[$level], $message, $level);
    }

    /**
     * Check for filesystem consistency
     *
     * @return bool
     *
     * @throws LogTypeNotSupportedException
     * @throws LogFileInvalidException
     * @throws ServiceNotFoundException
     * @throws ConfigInvalidException
     */
    private static function _checkConsistency()
    {
        /** @var Config $config */
        $config  = self::$serviceLocator->get(Config::class);
        $appRoot = realpath($config->get('projectRoot'));
        $logDirs = $config->get('log');

        if ($logDirs === null) {
            return true;
        }

        $hasInfoTypeInConf = false;

        if (in_array('info', array_keys($logDirs))) {
            $hasInfoTypeInConf = true;
        }

        foreach ($logDirs as $level => $path) {

            if (!in_array($level, self::$levels, true)) {

                throw new LogTypeNotSupportedException(
                    'Log type "' . $level . '" is currently not supported.'
                    . 'Please use one of the following log types:' . PHP_EOL
                    . implode(", ", self::$levels)
                );

            }

            $lastDirectorySeparator = strrpos($path, '/');

            if ($lastDirectorySeparator === false) {
                throw new LogFileInvalidException(
                    'You have to define the logfile path as an absolute path beginning from your project root'
                    . ' (i.e. if "/var/www/vhosts/app" is your project root set the logfile path to "/logs/{$level}.log")'
                );
            }

            $logDir = substr($path, 0, $lastDirectorySeparator);

            if (!is_dir($appRoot . $logDir)) {
                mkdir($appRoot . $logDir, 0774, true);
            }

            $file     = substr($path, $lastDirectorySeparator, strlen($path));
            $fullPath = $appRoot . $logDir . $file;
            $fileType = strrpos($fullPath, '.');

            if ($fileType !== false) {
                $fileType = substr($fullPath, $fileType, strlen($fullPath));
            }

            if (!file_exists($fullPath)) {
                file_put_contents($fullPath, '');
                chmod($fullPath, 0744);
            }

            if (!$hasInfoTypeInConf && !file_exists($appRoot . $logDir . '/info' . $fileType)) {

                file_put_contents($appRoot . $logDir . '/info' . $fileType, '');
                chmod($appRoot . $logDir . '/info' . $fileType, 0744);

                self::$logFiles[self::LEVEL_INFO] = $appRoot . $logDir . '/info' . $fileType;

            } else if (!$hasInfoTypeInConf) {

                self::$logFiles[self::LEVEL_INFO] = $appRoot . $logDir . '/info' . $fileType;

            }

            self::$logFiles[$level] = $fullPath;

        }

        self::$fileSystemChecked = true;

        return true;
    }

}