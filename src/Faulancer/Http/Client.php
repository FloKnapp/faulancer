<?php

namespace Faulancer\Http;

/**
 * Class Client
 *
 * @package Faulancer\Http
 * @author Florian Knapp <office@florianknapp.de>
 */
class Client
{

    /**
     * @param string   $url
     * @param string[] $headers
     * @return string
     */
    public static function get($url, $headers = [])
    {
        return self::sendCurl($url, $headers);
    }

    /**
     * @param string   $url
     * @param string[] $headers
     * @return string
     */
    protected static function sendCurl($url, $headers)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

}