<?php

namespace Faulancer\Http;

/**
 * Class JsonResponse
 *
 * @package Faulancer\Http
 * @author  Florian Knapp <office@florianknapp.de>
 */
class JsonResponse extends Response
{

    /** @var array */
    protected $content = [];

    /**
     * Set content
     *
     * @param array $content
     *
     * @return self
     */
    public function setContent($content = [])
    {
        $this->setResponseHeader(['Content-Type' => 'application/json']);

        $this->content = json_encode($content);
        return $this;
    }

}