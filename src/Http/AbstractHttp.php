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

    /** @var SessionManager */
    protected $session;

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