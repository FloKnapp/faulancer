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
        return self::sendCurl($uri, $headers);
    }

    /**
     * Send request within curl
     *
     * @param string   $uri
     * @param string[] $headers
     * @return string
     */
    protected static function sendCurl(string $uri, $headers) :string
    {
        $ch = curl_init($uri);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

}