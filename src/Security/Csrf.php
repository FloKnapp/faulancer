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
    /**
     * Generates a token and save it to session
     *
     * @param string $identifier
     *
     * @return string
     */
    public static function getToken(string $identifier = '') :string
    {
        $token = self::getSessionManager()->get('csrf' . $identifier);

        if (!self::getSessionManager()->has('csrf' . $identifier)) {
            $token = bin2hex(openssl_random_pseudo_bytes(16));
            self::saveToSession($token, $identifier);
        }

        return $token;
    }

    /**
     * Check if token is valid
     *
     * @param string $token
     * @param string $identifier
     *
     * @return bool
     */
    public static function isValid(string $token, string $identifier = '') :bool
    {
        $sessionToken = self::getSessionManager()->get('csrf' . $identifier);
        $isValid      = $token === $sessionToken;

        if ($isValid) {
            self::getSessionManager()->delete('csrf' . $identifier);
            return true;
        }

        return false;
    }

    /**
     * Saves token into session
     *
     * @param string $token
     * @param string $identifier
     */
    private static function saveToSession(string $token, string $identifier = '')
    {
        self::getSessionManager()->set('csrf' . $identifier, $token);
    }

    /**
     * @return SessionManagerService|ServiceInterface
     */
    private static function getSessionManager()
    {
        return ServiceLocator::instance()->get(SessionManagerService::class);
    }

}