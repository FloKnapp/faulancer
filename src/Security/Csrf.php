<?php
/**
 * Class Csrf
 *
 * @package Faulancer\Security
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Security;

use Faulancer\Session\SessionManager;

/**
 * Class Csrf
 */
class Csrf
{

    /**
     * Generates a token and save it to session
     * @return string
     */
    public static function getToken() :string
    {
        $token = bin2hex(openssl_random_pseudo_bytes(32));
        self::saveToSession($token);
        return $token;
    }

    /**
     * Check if token is valid
     * @return bool
     */
    public static function isValid() :bool
    {
        return isset($_POST['csrf']) && $_POST['csrf'] === SessionManager::instance()->getFlashbag('csrf');
    }

    /**
     * Saves token into session
     * @param string $token
     */
    private static function saveToSession(string $token)
    {
        SessionManager::instance()->setFlashbag('csrf', $token);
    }

}