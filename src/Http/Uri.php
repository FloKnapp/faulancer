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
     * @param string  $location
     * @param integer $code
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function redirect(string $location, int $code = 301)
    {

        if (in_array($code, array_keys(Response::HTTP_STATUS_CODES))) {

            header('HTTP/2 ' . $code . ' ' . Response::HTTP_STATUS_CODES[$code]);
            header('Location: ' .  $location);
            return $this->terminate();

        }

        throw new InvalidArgumentException('Target url is invalid or status code is unknown');
    }

    /**
     * @codeCoverageIgnore
     */
    public function terminate()
    {
        exit(0);
    }

}