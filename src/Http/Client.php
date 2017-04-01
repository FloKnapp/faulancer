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
        return self::sendCurl('GET', $uri, $headers);
    }

    /**
     * @param string $uri
     * @param array  $headers
     * @param array  $data
     * @return string
     */
    public static function post(string $uri, array $headers = [], array $data = []) :string
    {
        return self::sendCurl('POST', $uri, $headers, $data);
    }

    /**
     * Send request within curl
     *
     * @param string   $method
     * @param string   $uri
     * @param string[] $headers
     * @param string[] $data
     * @return string
     */
    protected static function sendCurl($method = 'GET', string $uri, $headers = [], $data = []) :string
    {
        $ch = curl_init($uri);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        if ($method === 'POST' && !empty($data)) {

            $fields = '';

            foreach ($data as $key => $value) {
                $fields .= $key . '=' . $value . '&';
            }

            substr($fields, 0, strlen($fields) - 1);

            curl_setopt($ch, CURLOPT_POST, count($data));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        }

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

}