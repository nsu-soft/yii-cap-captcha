<?php

namespace nsusoft\captcha\integrations\cap\api;

use nsusoft\captcha\integrations\cap\formatters\JsonFormatter;
use yii\helpers\Json;

class Main extends AbstractApi
{
    /**
     * @see http://localhost:3000/swagger#tag/challenges/POST/{siteKey}/challenge
     */
    public function challenge(string $siteKey): object
    {
        $uri = $this->factory->createUri("{$this->getBaseUri()}/{$siteKey}/challenge");
        
        $request = $this->factory->createRequest('POST', $uri);
        $response = $this->client->sendRequest($request);

        return JsonFormatter::fromResponse($response);
    }

    /**
     * @see http://localhost:3000/swagger#tag/challenges/POST/{siteKey}/redeem
     */
    public function redeem(string $siteKey, array $data): object
    {
        $uri = $this->factory->createUri("{$this->getBaseUri()}/{$siteKey}/redeem");
        $stream = $this->factory->createStream(Json::encode($data));
        
        $request = $this->factory->createRequest('POST', $uri)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($stream);
        
        $response = $this->client->sendRequest($request);

        return JsonFormatter::fromResponse($response);
    }

    /**
     * @see http://localhost:3000/swagger#tag/challenges/POST/{siteKey}/siteverify
     */
    public function siteverify(string $siteKey, array $data): object
    {
        $uri = $this->factory->createUri("{$this->getBaseUri()}/{$siteKey}/siteverify");
        $stream = $this->factory->createStream(Json::encode($data));
        
        $request = $this->factory->createRequest('POST', $uri)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($stream);
        
        $response = $this->client->sendRequest($request);

        return JsonFormatter::fromResponse($response);
    }
}