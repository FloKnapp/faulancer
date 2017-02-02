<?php
/**
 * Class Uri
 *
 * @package Faulancer\Http
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Http;

use Faulancer\Exception\InvalidArgumentException;

/**
 * Class Uri
 */
class Uri
{

    /**
     * Redirect to specific uri path
     *
     * @param string  $location The path as string
     * @param integer $code     The status code
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function redirect(string $location, int $code = 301)
    {

        if (in_array($code, array_keys(Response::HTTP_STATUS_CODES))) {

            header('HTTP/2 ' . $code . ' ' . Response::HTTP_STATUS_CODES[$code]);
            header('Location: ' .  $location);
            $this->terminate();
            return true;
        }

        throw new InvalidArgumentException('Target url is invalid or status code is unknown');
    }

    /**
     * Workaround to mock this method in phpunit
     *
     * @codeCoverageIgnore
     * @return void
     */
    protected function terminate()
    {
        exit(0);
    }

}