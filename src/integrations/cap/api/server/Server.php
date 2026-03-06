<?php

namespace nsusoft\captcha\integrations\cap\api\server;

use nsusoft\captcha\integrations\cap\api\AbstractApi;
use nsusoft\captcha\integrations\cap\formatters\JsonFormatter;
use stdClass;
use yii\helpers\Json;

class Server extends AbstractApi
{
    /**
     * @see http://localhost:3000/swagger#GET/server/about
     * @return stdClass
     */
    public function about(): stdClass
    {
        $uri = $this->factory->createUri("{$this->getBaseUri()}/server/about");
        
        $request = $this->factory->createRequest('GET', $uri)
            ->withHeader('Authorization', $this->getAuthorizationHeader());
        
        $response = $this->client->sendRequest($request);

        return JsonFormatter::fromResponse($response);
    }

    /**
     * @see http://localhost:3000/swagger#tag/settings/POST/server/logout
     * @param string $session
     * @return stdClass
     */
    public function logout(string $session): stdClass
    {
        $uri = $this->factory->createUri("{$this->getBaseUri()}/server/logout");
        $stream = $this->factory->createStream(Json::encode(['session' => $session]));
        
        $request = $this->factory->createRequest('POST', $uri)
            ->withHeader('Authorization', $this->getAuthorizationHeader())
            ->withHeader('Content-Type', 'application/json')
            ->withBody($stream);
        
        $response = $this->client->sendRequest($request);

        return JsonFormatter::fromResponse($response);
    }
}