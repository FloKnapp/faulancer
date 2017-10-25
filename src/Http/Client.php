<?php

namespace Faulancer\Http;

use Faulancer\Http\Adapter\AbstractAdapter;

/**
 * Class Client
 *
 * @package Faulancer\Http
 * @author Florian Knapp <office@florianknapp.de>
 */
class Client
{

    /** @var AbstractAdapter */
    protected $adapter;

    /**
     * Client constructor.
     * @param AbstractAdapter $adapter
     */
    public function __construct(AbstractAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Get resource by uri
     *
     * @param string   $uri     The uri
     * @param string[] $headers Custom headers
     * @return string
     */
    public function get(string $uri, array $headers = []) :string
    {
        $request = new Request();
        $request->setMethod('GET');
        $request->setUri($uri);
        $request->setHeaders($headers);

        $this->adapter->send($request);

        return $this->adapter->getResponse();
    }

    /**
     * @param string $uri
     * @param array  $headers
     * @param array  $data
     * @return string
     */
    public function post(string $uri, array $headers = [], array $data = []) :string
    {
        $request = new Request();
        $request->setMethod('POST');
        $request->setUri($uri);
        $request->setHeaders($headers);
        $request->setBody($data);

        $this->adapter->send($request);

        return $this->adapter->getResponse();
    }

}