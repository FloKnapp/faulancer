<?php

namespace Faulancer\Http;

/**
 * Class Response
 *
 * @package Faulancer\Http
 * @author Florian Knapp <office@florianknapp.de>
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

    /** @var integer */
    protected $code = 200;

    /** @var string */
    protected $content;

    /**
     * @param integer $code
     */
    public function setCode(int $code = 200)
    {
        $this->code = $code;
    }

    /**
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/2.0';

        header($protocol . ' ' . $this->getCode() . ' ' . self::HTTP_STATUS_CODES[$this->getCode()]);
        return $this->content;
    }

    public function __toString()
    {
        return (string)$this->getContent();
    }

}