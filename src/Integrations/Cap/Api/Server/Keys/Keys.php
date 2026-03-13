<?php

namespace NsuSoft\Captcha\Integrations\Cap\Api\Server\Keys;

use NsuSoft\Captcha\Integrations\Cap\Api\AbstractApi;
use NsuSoft\Captcha\Integrations\Cap\Formatters\JsonFormatter;
use stdClass;
use yii\helpers\Json;

class Keys extends AbstractApi
{
    /**
     * @see http://localhost:3000/swagger#tag/keys/GET/server/keys
     * @return array
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
     * @param string $name
     * @return stdClass
     */
    public function create(string $name): stdClass
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
     * @param string $siteKey
     * @return stdClass
     */
    public function view(string $siteKey): stdClass
    {
        $uri = $this->factory->createUri("{$this->getBaseUri()}/server/keys/{$siteKey}");
        
        $request = $this->factory->createRequest('GET', $uri)
            ->withHeader('Authorization', $this->getAuthorizationHeader());
        
        $response = $this->client->sendRequest($request);

        return JsonFormatter::fromResponse($response);
    }

    /**
     * @see http://localhost:3000/swagger#tag/keys/DELETE/server/keys/{siteKey}
     * @param string $siteKey
     * @return stdClass
     */
    public function delete(string $siteKey): stdClass
    {
        $uri = $this->factory->createUri("{$this->getBaseUri()}/server/keys/{$siteKey}");
        
        $request = $this->factory->createRequest('DELETE', $uri)
            ->withHeader('Authorization', $this->getAuthorizationHeader());
        
        $response = $this->client->sendRequest($request);

        return JsonFormatter::fromResponse($response);
    }

    /**
     * @see http://localhost:3000/swagger#tag/keys/PUT/server/keys/{siteKey}/config
     * @param string $siteKey
     * @param array $options
     * @return stdClass
     */
    public function config(string $siteKey, array $options = []): stdClass
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
     * @param string $siteKey
     * @return stdClass
     */
    public function rotateSecret(string $siteKey): stdClass
    {
        $uri = $this->factory->createUri("{$this->getBaseUri()}/server/keys/{$siteKey}/rotate-secret");
        
        $request = $this->factory->createRequest('POST', $uri)
            ->withHeader('Authorization', $this->getAuthorizationHeader());
        
        $response = $this->client->sendRequest($request);

        return JsonFormatter::fromResponse($response);
    }
}