<?php

namespace NsuSoft\Captcha\Integrations\Cap\Api;

use NsuSoft\Captcha\Integrations\Cap\Formatters\JsonFormatter;
use stdClass;
use yii\helpers\Json;

class Main extends AbstractApi
{
    /**
     * @see http://localhost:3000/swagger#tag/challenges/POST/{siteKey}/challenge
     * @param string $siteKey
     * @return stdClass
     */
    public function challenge(string $siteKey): stdClass
    {
        $uri = $this->factory->createUri("{$this->getBaseUri()}/{$siteKey}/challenge");
        
        $request = $this->factory->createRequest('POST', $uri);
        $response = $this->client->sendRequest($request);

        return JsonFormatter::fromResponse($response);
    }

    /**
     * @see http://localhost:3000/swagger#tag/challenges/POST/{siteKey}/redeem
     * @param string $siteKey
     * @param array $data
     * @return stdClass
     */
    public function redeem(string $siteKey, array $data): stdClass
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
     * @param string $siteKey
     * @param array $data
     * @return stdClass
     */
    public function siteverify(string $siteKey, array $data): stdClass
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