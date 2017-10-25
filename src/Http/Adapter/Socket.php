<?php

namespace Faulancer\Http\Adapter;

use Faulancer\Http\Request;

/**
 * Class Socket
 * @package Faulancer\Http\Adapter
 * @author  Florian Knapp <office@florianknapp.de>
 */
class Socket extends AbstractAdapter
{

    /**
     * @param Request $request
     * @return void
     *
     * @codeCoverageIgnore
     */
    public function send(Request $request)
    {
        $url = $request->getUri();

        $port     = strpos($url, 'https://') !== false ? 443 : 80;
        $protocol = $port === 443 ? 'ssl://' : 'tcp://';

        $url = str_replace(['https://', 'http://'], '', $url);

        $req = '';

        foreach ($request->getHeaders() as $header => $value) {
            $req .= $header . ': ' . $value . "\r\n";
        }

        if (is_array($request->getBody())) {
            $requestBody = implode('', $request->getBody());
        } else {
            $requestBody = $request->getBody();
        }

        $req .= 'Content-Type: text/html' . "\r\n";
        $req .= 'Content-Length: ' . strlen($requestBody) . "\r\n";
        $req .= 'Connection: close' . "\r\n\r\n";

        $socket = stream_socket_client($protocol . $url . ':' . $port,$err, $errstr, 60, STREAM_CLIENT_CONNECT);

        fputs($socket, $req);

        $res = '';

        while(!feof($socket)) { $res .= fgets($socket, 128); }
        fclose($socket);

        $this->response = $res;
    }

    /**
     * @return null|mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

}