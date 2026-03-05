<?php

namespace nsusoft\captcha\adapters\yii;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client implements ClientInterface
{
    /**
     * @inheritDoc
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $response = RequestAdapter::toYii($request)->send();
        return ResponseAdapter::toPsr($response);
    }
}