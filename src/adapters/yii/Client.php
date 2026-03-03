<?php

namespace nsusoft\captcha\adapters\yii;

use Http\Discovery\Psr17Factory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client implements RequestFactoryInterface, ClientInterface
{
    /**
     * @inheritDoc
     */
    public function createRequest(string $method, $uri): RequestInterface
    {
        return (new Psr17Factory())->createRequest($method, $uri);
    }

    /**
     * @inheritDoc
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $response = RequestAdapter::toYii($request)->send();
        return ResponseAdapter::toPsr($response);
    }
}