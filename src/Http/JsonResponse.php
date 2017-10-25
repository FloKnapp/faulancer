<?php
/**
 * Class JsonResponse | JsonResponse.php
 * @package Faulancer\Http
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Http;

/**
 * Class JsonResponse
 */
class JsonResponse extends Response
{

    protected $content = [];

    /**
     * @param array $content
     * @return self
     */
    public function setContent($content = [])
    {
        $this->setResponseHeader(['Content-Type' => 'application/json']);

        $this->content = json_encode($content);
        return $this;
    }

}