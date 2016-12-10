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

    const HTTP_STATUS_CODES = [
        200 => 'Ok',
        301 => 'Moved Permanently',
        304 => 'Not Modified',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        408 => 'Request Timeout',
        410 => 'Gone',
        418 => 'I\'m a teapot',
        429 => 'Too Many Requests',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timed-out',
        505 => 'HTTP Version Not Supported',
        507 => 'Insufficient Storage',

    ];

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
     * @throws InvalidArgumentException
     */
    public static function redirect(string $location, int $code = 301)
    {
        if (in_array($code, array_keys(self::HTTP_STATUS_CODES))) {

            header('HTTP/2 ' . $code . ' ' .self::HTTP_STATUS_CODES[$code]);
            header('Location: ' .  $location);
            exit(0);

        }

        throw new InvalidArgumentException('Target url is invalid or status code is unknown');
    }

}