<?php

namespace Faulancer\Log\Writer;

/**
 * Class DefaultWriter | DefaultWriter.php
 *
 * @package Faulancer\Log
 * @author  Florian Knapp <office@florianknapp.de>
 */
class DefaultWriter extends AbstractWriter
{

    /**
     * Write default log format
     *
     * @param string $logfile
     * @param string $message
     * @param string $level
     *
     * @return int|bool
     */
    public function write(string $logfile, string $message, string $level)
    {
        $columns = [
            (new \DateTime())->format('d.m.Y H:i:s'),
            strtoupper($level),
            $message
        ];

        $messageString  = implode(' | ', $columns) . "\r\n";

        return file_put_contents($logfile, $messageString, FILE_APPEND);
    }

}