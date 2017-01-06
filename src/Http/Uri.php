<?php

namespace Faulancer\Http;

use Faulancer\Exception\InvalidArgumentException;

/**
 * Class Uri
 *
 * @package Core\Utility
 * @author Florian Knapp <office@florianknapp.de>
 */
class Uri
{

    /**
     * @return string
     */
    public static function getUri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * @param string  $location
     * @param integer $code
     * @return boolean
     * @throws InvalidArgumentException
     */
    public static function redirect(string $location, int $code = 301)
    {
        if (in_array($code, array_keys(Response::HTTP_STATUS_CODES))) {

            header('HTTP/2 ' . $code . ' ' . Response::HTTP_STATUS_CODES[$code]);
            header('Location: ' .  $location);
            exit(0);

        }

        throw new InvalidArgumentException('Target url is invalid or status code is unknown');
    }

}