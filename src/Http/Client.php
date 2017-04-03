<?php
/**
 * Class Client
 *
 * @package Faulancer\Http
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Http;

/**
 * Class Client
 */
class Client
{

    /**
     * Get resource by uri
     *
     * @param string   $uri     The uri
     * @param string[] $headers Custom headers
     * @return string
     */
    public static function get(string $uri, array $headers = []) :string
    {
        $request = new Request();
        $request->setMethod('GET');
        $request->setUri($uri);
        $request->setHeaders($headers);

        return self::sendCurl($request);
    }

    /**
     * @param string $uri
     * @param array  $headers
     * @param array  $data
     * @return string
     */
    public static function post(string $uri, array $headers = [], array $data = []) :string
    {
        $request = new Request();
        $request->setMethod('GET');
        $request->setUri($uri);
        $request->setHeaders($headers);
        $request->setBody($data);

        return self::sendCurl($request);
    }

    /**
     * Send request within curl
     *
     * @param Request $request
     * @return string
     * @codeCoverageIgnore
     */
    protected static function sendCurl(Request $request) :string
    {
        $ch = curl_init($request->getUri());

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (!empty($request->getHeaders())) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $request->getHeaders());
        }

        if ($request->getMethod() === 'POST' && !empty($request->getBody())) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getBody());
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

}