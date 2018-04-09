<?php

namespace Faulancer\Security;

use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\Service\SessionManagerService;
use Faulancer\ServiceLocator\ServiceInterface;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class Csrf
 *
 * @package Faulancer\Security
 * @author Florian Knapp <office@florianknapp.de>
 */
class Csrf
{
    /**
     * Generates a token and save it to session
     *
     * @param string $identifier
     *
     * @return string
     *
     * @throws ServiceNotFoundException
     */
    public static function getToken(string $identifier = '') :string
    {
        $token = self::_getSessionManager()->get('csrf' . $identifier);

        if (!self::_getSessionManager()->has('csrf' . $identifier)) {
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
     *
     * @throws ServiceNotFoundException
     */
    public static function isValid(string $token, string $identifier = '') :bool
    {
        $sessionToken = self::_getSessionManager()->get('csrf' . $identifier);
        $isValid      = $token === $sessionToken;

        if ($isValid) {
            self::_getSessionManager()->delete('csrf' . $identifier);
            return true;
        }

        return false;
    }

    /**
     * Saves token into session
     *
     * @param string $token
     * @param string $identifier
     *
     * @return void
     *
     * @throws ServiceNotFoundException
     */
    private static function saveToSession(string $token, string $identifier = '')
    {
        self::_getSessionManager()->set('csrf' . $identifier, $token);
    }

    /**
     * @return SessionManagerService|ServiceInterface
     *
     * @throws ServiceNotFoundException
     */
    private static function _getSessionManager()
    {
        return ServiceLocator::instance()->get(SessionManagerService::class);
    }

}