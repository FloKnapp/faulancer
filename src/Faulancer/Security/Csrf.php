<?php

namespace Faulancer\Security;



use Faulancer\Session\SessionManager;

class Csrf
{

    /**
     * Generates a token and save it to session
     *
     * @return string
     */
    public static function getToken()
    {
        $token = bin2hex(random_bytes(32));
        self::saveToSession($token);
        return $token;
    }

    private static function saveToSession($token)
    {
        SessionManager::instance()->setFlashbag('csrf', $token);
    }

}