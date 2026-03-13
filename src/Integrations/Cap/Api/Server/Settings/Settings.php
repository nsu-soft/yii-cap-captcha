<?php

namespace NsuSoft\Captcha\Integrations\Cap\Api\Server\Settings;

use NsuSoft\Captcha\Integrations\Cap\Api\AbstractApi;
use NsuSoft\Captcha\Integrations\Cap\Formatters\JsonFormatter;

class Settings extends AbstractApi
{
    /**
     * @see http://localhost:3000/swagger#tag/settings/GET/server/settings/sessions
     * @return array
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