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

    /** @var string */
    protected $query = '';

    /**
     * @return void
     */
    public function createFromHeaders()
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {
            $uri = explode('?', $_SERVER['REQUEST_URI']);
            $this->setQuery($uri[1]);
            $uri = $uri[0];
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
     * @param string $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
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