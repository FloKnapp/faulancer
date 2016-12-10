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
    public function isPost()
    {
        return count($_POST) ? true : false;
    }

    /**
     * @return boolean
     */
    public function isGet()
    {
        return count($_GET) ? true : false;
    }

    /**
     * @return array
     */
    public function getPostData()
    {
        return empty($_POST) ? [] : $_POST;
    }

}