<?php

namespace Faulancer\Http\Adapter;

use Faulancer\Exception\ClientException;
use Faulancer\Http\Request;

/**
 * Class Curl
 * @package Faulancer\Http\Adapter
 * @author  Florian Knapp <office@florianknapp.de>
 */
class Curl extends AbstractAdapter
{
    /**
     * @param Request $request
     * @return void
     */
    public function send(Request $request)
    {
        $ch = curl_init($request->getUri());

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (!empty($request->getHeaders())) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $request->getHeaders());
        }

        if ($request->getMethod() === 'POST' && !empty($request->getBody())) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getBody());
        }

        $this->response = curl_exec($ch);
        curl_close($ch);
    }

    /**
     * @return mixed
     * @throws ClientException
     */
    public function getResponse()
    {
        if (empty($this->response)) {
            throw new ClientException('You have to call the \'send\' method first.');
        }

        return $this->response;
    }

}