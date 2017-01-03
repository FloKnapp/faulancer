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

    /** @var string */
    protected $uri = '';

    /** @var string */
    protected $method = '';

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
     * @param string $uri
     */
    public function setUri(string $uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return empty($this->method) ? $_SERVER['REQUEST_METHOD'] : $this->method;
    }

    /**
     * @return boolean
     */
    public function isPost()
    {
        return $this->getMethod() === 'POST';
    }

    /**
     * @return boolean
     */
    public function isGet()
    {
        return $this->getMethod() === 'GET';
    }

    /**
     * @return array
     */
    public function getPostData()
    {
        return empty($_POST) ? [] : $_POST;
    }

}