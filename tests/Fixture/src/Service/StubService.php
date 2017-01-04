<?php

namespace Faulancer\Fixture\Service;

/**
 * File StubService.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class StubService
{

    protected $dependency = '';

    /**
     * StubService constructor.
     *
     * @param string $dependency
     */
    public function __construct($dependency)
    {
        $this->dependency = $dependency;
    }

    public function getDependency()
    {
        return $this->dependency;
    }

}