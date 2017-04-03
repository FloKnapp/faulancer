<?php
/**
 * Class Request
 *
 * @package Faulancer\Http
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Http;

use Faulancer\Exception\InvalidArgumentException;

/**
 * Class Request
 */
class Request extends AbstractHttp
{

    /**
     * The current path string
     * @var string
     */
    protected $uri = '';

    /**
     * The current method
     * @var string
     */
    protected $method = '';

    /**
     * Custom headers
     * @var array
     */
    protected $headers = [];

    /**
     * The current query string
     * @var string
     */
    protected $query = '';

    /**
     * @var string
     */
    protected $body = '';

    /**
     * Set attributes automatically
     *
     * @return self
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

        return $this;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers = [])
    {
        $this->headers = $headers;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set uri path
     *
     * @param string $uri
     */
    public function setUri(string $uri)
    {
        if (strpos($uri, '?') !== false) {
            $uri = explode('?', $uri);
            $this->setQuery($uri[1]);
            $uri = $uri[0];
        }

        $this->uri = $uri;
    }

    /**
     * Get uri path
     *
     * @return string
     */
    public function getUri() :string
    {
        return $this->uri;
    }

    /**
     * Set method
     *
     * @param string $method
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    /**
     * Get method
     *
     * @return string
     */
    public function getMethod() :string
    {
        $serverRequestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
        return empty($this->method) ? $serverRequestMethod : $this->method;
    }

    /**
     * Set query string
     *
     * @param string $query
     */
    public function setQuery(string $query)
    {
        $this->query = $query;
    }

    /**
     * Get query string
     *
     * @return string
     */
    public function getQuery() :string
    {
        return $this->query;
    }

    /**
     * @param array $body
     */
    public function setBody($body)
    {
       $this->body = $body;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Determine if it's a post request
     *
     * @return boolean
     */
    public function isPost() :bool
    {
        return $this->getMethod() === 'POST';
    }

    /**
     * Determine if it's a get request
     *
     * @return boolean
     */
    public function isGet() :bool
    {
        return $this->getMethod() === 'GET';
    }

    /**
     * @param string $key
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function getParam(string $key)
    {
        $post = !empty($_POST) ? $_POST : [];
        $get  = !empty($_GET) ? $_GET : [];

        if (!empty($this->getQuery())) {
            parse_str($this->getQuery(), $get);
        }

        $combined = $post + $get;

        if (!empty($combined[$key])) {
            return $combined[$key];
        }

        return null;
    }

    /**
     * Return the post data
     *
     * @return array
     */
    public function getPostData() :array
    {
        return empty($_POST) ? [] : $_POST;
    }

    /**
     * @param $data
     * @return void
     */
    public function setPostData($data)
    {
        $_POST = $data;
    }

}