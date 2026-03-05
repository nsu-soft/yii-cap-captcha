<?php

namespace nsusoft\captcha\integrations\cap\api\server\keys;

use nsusoft\captcha\integrations\cap\api\AbstractApi;
use nsusoft\captcha\integrations\cap\formatters\JsonFormatter;
use yii\helpers\Json;

class Keys extends AbstractApi
{
    /**
     * @see http://localhost:3000/swagger#tag/keys/GET/server/keys
     */
    public function index(): array
    {
        $uri = $this->factory->createUri("{$this->getBaseUri()}/server/keys");
        
        $request = $this->factory->createRequest('GET', $uri)
            ->withHeader('Authorization', $this->getAuthorizationHeader());
        
        $response = $this->client->sendRequest($request);

        return JsonFormatter::fromResponse($response);
    }

    /**
     * @see http://localhost:3000/swagger#tag/keys/POST/server/keys
     */
    public function create(string $name): object
    {
        $uri = $this->factory->createUri("{$this->getBaseUri()}/server/keys");
        $stream = $this->factory->createStream(Json::encode(['name' => $name]));
        
        $request = $this->factory->createRequest('POST', $uri)
            ->withHeader('Authorization', $this->getAuthorizationHeader())
            ->withHeader('Content-Type', 'application/json')
            ->withBody($stream);
        
        $response = $this->client->sendRequest($request);

        return JsonFormatter::fromResponse($response);
    }

    /**
     * @see http://localhost:3000/swagger#tag/keys/GET/server/keys/{siteKey}
     */
    public function view(string $siteKey)
    {
        $uri = $this->factory->createUri("{$this->getBaseUri()}/server/keys/{$siteKey}");
        
        $request = $this->factory->createRequest('GET', $uri)
            ->withHeader('Authorization', $this->getAuthorizationHeader());
        
        $response = $this->client->sendRequest($request);

        return JsonFormatter::fromResponse($response);
    }

    /**
     * @see http://localhost:3000/swagger#tag/keys/DELETE/server/keys/{siteKey}
     */
    public function delete(string $siteKey)
    {
        $uri = $this->factory->createUri("{$this->getBaseUri()}/server/keys/{$siteKey}");
        
        $request = $this->factory->createRequest('DELETE', $uri)
            ->withHeader('Authorization', $this->getAuthorizationHeader());
        
        $response = $this->client->sendRequest($request);

        return JsonFormatter::fromResponse($response);
    }

    /**
     * @see http://localhost:3000/swagger#tag/keys/PUT/server/keys/{siteKey}/config
     */
    public function config(string $siteKey, $options = [])
    {
        $uri = $this->factory->createUri("{$this->getBaseUri()}/server/keys/{$siteKey}/config");
        $stream = $this->factory->createStream(Json::encode($options));
        
        $request = $this->factory->createRequest('PUT', $uri)
            ->withHeader('Authorization', $this->getAuthorizationHeader())
            ->withHeader('Content-Type', 'application/json')
            ->withBody($stream);
        
        $response = $this->client->sendRequest($request);

        return JsonFormatter::fromResponse($response);
    }

    /**
     * @see http://localhost:3000/swagger#tag/keys/POST/server/keys/{siteKey}/rotate-secret
     */
    public function rotateSecret(string $siteKey)
    {
        $uri = $this->factory->createUri("{$this->getBaseUri()}/server/keys/{$siteKey}/rotate-secret");
        
        $request = $this->factory->createRequest('POST', $uri)
            ->withHeader('Authorization', $this->getAuthorizationHeader());
        
        $response = $this->client->sendRequest($request);

        return JsonFormatter::fromResponse($response);
    }
}