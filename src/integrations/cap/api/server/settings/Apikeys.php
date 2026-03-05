<?php

namespace nsusoft\captcha\integrations\cap\api\server\settings;

use nsusoft\captcha\integrations\cap\api\AbstractApi;
use nsusoft\captcha\integrations\cap\formatters\JsonFormatter;
use yii\helpers\Json;

class Apikeys extends AbstractApi
{
    /**
     * @see http://localhost:3000/swagger#tag/settings/GET/server/settings/apikeys
     */
    public function index(): array
    {
        $uri = $this->factory->createUri("{$this->getBaseUri()}/server/settings/apikeys");
        
        $request = $this->factory->createRequest('GET', $uri)
            ->withHeader('Authorization', $this->getAuthorizationHeader());
        
        $response = $this->client->sendRequest($request);

        return JsonFormatter::fromResponse($response);
    }

    /**
     * @see http://localhost:3000/swagger#tag/settings/POST/server/settings/apikeys
     */
    public function create(string $name): object
    {
        $uri = $this->factory->createUri("{$this->getBaseUri()}/server/settings/apikeys");
        $stream = $this->factory->createStream(Json::encode(['name' => $name]));
        
        $request = $this->factory->createRequest('POST', $uri)
            ->withHeader('Authorization', $this->getAuthorizationHeader())
            ->withHeader('Content-Type', 'application/json')
            ->withBody($stream);
        
        $response = $this->client->sendRequest($request);

        return JsonFormatter::fromResponse($response);
    }

    /**
     * @see http://localhost:3000/swagger#tag/settings/DELETE/server/settings/apikeys/{id}
     */
    public function delete(string $id): object
    {
        $uri = $this->factory->createUri("{$this->getBaseUri()}/server/settings/apikeys/{$id}");
        
        $request = $this->factory->createRequest('DELETE', $uri)
            ->withHeader('Authorization', $this->getAuthorizationHeader());
        
        $response = $this->client->sendRequest($request);

        return JsonFormatter::fromResponse($response);
    }
}