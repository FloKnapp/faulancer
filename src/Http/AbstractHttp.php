<?php
/**
 * Class AbstractHttp
 *
 * @package Faulancer\Http
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Http;

use Faulancer\Session\SessionManager;

/**
 * Class AbstractHttp
 */
abstract class AbstractHttp
{

    /**
     * The session manager
     *
     * @var SessionManager
     */
    protected $session;

    /**
     * Set session manager
     *
     * @param SessionManager $session
     */
    public function setSession(SessionManager $session)
    {
        $this->session = $session;
    }

    /**
     * Get current session manager
     *
     * @return SessionManager
     */
    public function getSession()
    {
        return $this->session;
    }

}