<?php
/**
 * Class Csrf
 *
 * @package Faulancer\Security
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Security;

use Faulancer\Service\SessionManagerService;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class Csrf
 */
class Csrf
{

    /**
     * Generates a token and save it to session
     *
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
     *
     * @return bool
     */
    public static function isValid() :bool
    {
        return isset($_POST['csrf']) && $_POST['csrf'] === self::getSessionManager()->getFlashbag('csrf');
    }

    /**
     * Saves token into session
     *
     * @param string $token
     */
    private static function saveToSession(string $token)
    {
        self::getSessionManager()->setFlashbag('csrf', $token);
    }

    /**
     * @return SessionManagerService
     */
    private static function getSessionManager()
    {
        return ServiceLocator::instance()->get(SessionManagerService::class);
    }

}