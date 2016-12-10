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

    /** @var integer */
    protected $code;

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
     * @param string $content
     */
    public function setContent(string $content = '')
    {
        $this->content = $content;
    }

    /**
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

}