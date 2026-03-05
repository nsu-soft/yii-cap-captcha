<?php

namespace nsusoft\captcha\integrations\cap\api\server\settings;

use nsusoft\captcha\integrations\cap\api\AbstractApi;
use nsusoft\captcha\integrations\cap\formatters\JsonFormatter;

class Settings extends AbstractApi
{
    /**
     * @see http://localhost:3000/swagger#tag/settings/GET/server/settings/sessions
     */
    public function sessions(): array
    {
        $uri = $this->factory->createUri("{$this->getBaseUri()}/server/settings/sessions");
        
        $request = $this->factory->createRequest('GET', $uri)
            ->withHeader('Authorization', $this->getAuthorizationHeader());
        
        $response = $this->client->sendRequest($request);

        return JsonFormatter::fromResponse($response);
    }
}