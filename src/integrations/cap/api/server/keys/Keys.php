<?php

namespace nsusoft\captcha\integrations\cap\api\server\keys;

use nsusoft\captcha\factories\ClientFactory;
use nsusoft\captcha\integrations\cap\api\AbstractApi;
use nsusoft\captcha\integrations\cap\formatters\JsonFormatter;

class Keys extends AbstractApi
{
    /**
     * @see http://localhost:3000/swagger#tag/keys/GET/server/keys
     */
    public function index()
    {
        $client = ClientFactory::create();
        $uri = $client->createUri("{$this->server}:{$this->port}/server/keys");

        $request = $client->createRequest('GET', $uri)
            ->withHeader('Authorization', "Bot {$this->apiKey}");

        $response = $client->sendRequest($request);

        return JsonFormatter::fromResponse($response);
    }

    /**
     * @see http://localhost:3000/swagger#tag/keys/POST/server/keys
     */
    public function create()
    {

    }

    /**
     * @see http://localhost:3000/swagger#tag/keys/GET/server/keys/{siteKey}
     */
    public function view(string $siteKey)
    {

    }

    /**
     * @see http://localhost:3000/swagger#tag/keys/DELETE/server/keys/{siteKey}
     */
    public function delete(string $siteKey)
    {

    }

    /**
     * @see http://localhost:3000/swagger#tag/keys/PUT/server/keys/{siteKey}/config
     */
    public function config(string $siteKey)
    {

    }

    /**
     * @see http://localhost:3000/swagger#tag/keys/POST/server/keys/{siteKey}/rotate-secret
     */
    public function rotateSecret(string $siteKey)
    {

    }
}