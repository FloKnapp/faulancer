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
     * The current scheme
     * @var string
     */
    protected $scheme = '';

    /**
     * The current host
     * @var string
     */
    protected $host = '';

    /**
     * The current path string
     * @var string
     */
    protected $path = '';

    /**
     * The current uri
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
     * @var array
     */
    protected $get = [];

    /**
     * @var array
     */
    protected $post = [];

    /**
     * Set attributes automatically
     *
     * @return self
     */
    public function createFromHeaders()
    {
        $path = $_SERVER['REQUEST_URI'];

        if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {
            $path = explode('?', $_SERVER['REQUEST_URI']);
            $this->setQuery($path[1]);
            $path = $path[0];
        }

        $this->setScheme(!empty($_SERVER['HTTPS']) ? 'https://' : 'http://');
        $this->setHost($_SERVER['HTTP_HOST']);
        $this->setPath($path);
        $this->setMethod($_SERVER['REQUEST_METHOD']);

        return $this;
    }

    /**
     * Set the request scheme
     *
     * @param string $scheme
     */
    public function setScheme(string $scheme = '')
    {
        $this->scheme = $scheme;
    }

    /**
     * Get the request scheme
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
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
     * @param string $host
     */
    public function setHost(string $host = '')
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set uri path
     *
     * @param string $path
     */
    public function setPath(string $path)
    {
        if (strpos($path, '?') !== false) {
            $path = explode('?', $path);
            $this->setQuery($path[1]);
            $path = $path[0];
        }

        $this->path = $path;
    }

    /**
     * Get uri path
     *
     * @return string
     */
    public function getPath() :string
    {
        return $this->path;
    }

    /**
     * Set uri path
     *
     * @param string $uri
     */
    public function setUri(string $uri)
    {
        if (strpos($uri, '?') !== false) {
            $path = explode('?', $uri);
            $this->setQuery($path[1]);
            $uri = $path[0];
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
     *
     * @return mixed
     */
    public function getParam(string $key)
    {
        $this->post  = !empty($_POST) ? array_merge($_POST, $this->post) : [];
        $this->get   = !empty($_GET) ? array_merge($_GET, $this->get) : [];

        if (!empty($this->getQuery())) {

            $query = [];
            parse_str($this->getQuery(), $query);

            if (!empty($query[$key])) {
                return $query[$key];
            }

        }

        $combined = array_merge($this->post, $this->get);

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