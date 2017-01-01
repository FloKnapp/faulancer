<?php

namespace Faulancer\Command;

/**
 * Interface CommandInterface
 *
 * @package Faulancer\Command
 * @author  Florian Knapp <office@florianknapp.de>
 */
interface CommandInterface
{
    /**
     * @return mixed
     */
    public function run();
}