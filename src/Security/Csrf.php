<?php
/**
 * Class Csrf
 *
 * @package Faulancer\Security
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Security;

use Faulancer\Service\SessionManagerService;
use Faulancer\ServiceLocator\ServiceInterface;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class Csrf
 */
class Csrf
{

    protected static $count = 0;

    /**
     * Generates a token and save it to session
     *
     * @return string
     */
    public static function getToken() :string
    {
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        self::saveToSession($token);
        return $token;

    }

    /**
     * Check if token is valid
     *
     * @param string $token
     *
     * @return bool
     */
    public static function isValid(string $token) :bool
    {
        $sessionToken = self::getSessionManager()->getFlashMessage('csrf');
        return $token === $sessionToken;
    }

    /**
     * Saves token into session
     *
     * @param string $token
     */
    private static function saveToSession(string $token)
    {
        self::getSessionManager()->setFlashMessage('csrf', $token);
    }

    /**
     * @return SessionManagerService|ServiceInterface
     */
    private static function getSessionManager()
    {
        return ServiceLocator::instance()->get(SessionManagerService::class);
    }

}