<?php

namespace Faulancer\Http;

use Faulancer\Session\SessionManager;

/**
 * Class AbstractHttp
 *
 * @package Faulancer\Http
 * @author Florian Knapp <office@florianknapp.de>
 */
abstract class AbstractHttp
{

    /** @var string */
    protected $method;

    /** @var string */
    protected $uri;

    /** @var SessionManager */
    protected $session;

    /**
     * @param string $method
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $uri
     */
    public function setUri(string $uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param SessionManager $session
     */
    public function setSession(SessionManager $session)
    {
        $this->session = $session;
    }

    /**
     * @return SessionManager
     */
    public function getSession()
    {
        return $this->session;
    }

}