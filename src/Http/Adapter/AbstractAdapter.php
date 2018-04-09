<?php

namespace Faulancer\Http\Adapter;

use Faulancer\Http\Request;

/**
 * Class Adapter
 * @package Faulancer\Http
 * @author  Florian Knapp <office@florianknapp.de>
 */
abstract class AbstractAdapter
{

    /** @var null */
    protected $response = null;

    /**
     * @param Request $request
     *
     * @return static
     */
    abstract public function send(Request $request);

    /**
     * @return static
     */
    abstract public function getResponse();

}