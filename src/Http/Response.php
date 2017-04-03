<?php
/**
 * Class Response
 *
 * @package Faulancer\Http
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Http;

/**
 * Class Response
 */
class Response extends AbstractHttp
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
     * The status code (default: 200)
     * @var integer
     */
    protected $code = 200;

    /**
     * The response body
     * @var string
     */
    protected $content;

    /**
     * Response constructor.
     * @param mixed $content
     */
    public function __construct($content = null)
    {
        $this->setContent($content);
    }

    /**
     * Set response code
     *
     * @param integer $code
     */
    public function setCode(int $code = 200)
    {
        $this->code = $code;
    }

    /**
     * Get response code
     *
     * @return int
     */
    public function getCode() :int
    {
        return $this->code;
    }

    /**
     * Set response body
     *
     * @param mixed $content
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get response body and set headers
     *
     * @return mixed
     */
    public function getContent()
    {
        $this->setResponseHeader();
        return $this->content;
    }

    /**
     * @param array $headers
     * @codeCoverageIgnore Is covered because usage of php built-in function
     */
    public function setResponseHeader(array $headers = [])
    {
        $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/2.0';
        header($protocol . ' ' . $this->getCode() . ' ' . self::HTTP_STATUS_CODES[$this->getCode()] . PHP_EOL);

        if ($headers) {
            foreach ($headers as $name => $value) {
                header($name . ': ' . $value . PHP_EOL);
            }
        }
    }

    /**
     * If object is getting outputted
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getContent();
    }

}