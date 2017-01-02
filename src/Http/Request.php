<?php

namespace Faulancer\Http;

/**
 * Class Request
 *
 * @package Faulancer\Http
 * @author Florian Knapp <office@florianknapp.de>
 */
class Request extends AbstractHttp
{

    /**
     * @return void
     */
    public function createFromHeaders()
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {
            $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
        }

        $this->setUri($uri);
        $this->setMethod($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return boolean
     */
    public static function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * @return boolean
     */
    public static function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    public static function getRequestMethod()
    {
        return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
    }

    /**
     * @return array
     */
    public function getPostData()
    {
        return empty($_POST) ? [] : $_POST;
    }

}