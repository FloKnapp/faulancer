<?php

namespace Faulancer\Log\Writer;

use Faulancer\Service\Config;

/**
 * Class AbstractWriter | AbstractWriter.php
 *
 * @package Faulancer\Log
 * @author  Florian Knapp <office@florianknapp.de>
 */
abstract class AbstractWriter
{

    /** @var Config|null */
    protected $config = null;

    /** @var array */
    protected $backtrace = [];

    /**
     * AbstractWriter constructor.
     *
     * @param Config $config
     * @param array  $backtrace
     */
    public function __construct(Config $config, array $backtrace)
    {
        $this->config    = $config;
        $this->backtrace = $backtrace;
    }

    /**
     * @param string $logfile
     * @param string $message
     * @param string $level
     *
     * @return mixed
     */
    abstract public function write(string $logfile, string $message, string $level);

}