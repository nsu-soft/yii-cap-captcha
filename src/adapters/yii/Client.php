<?php

namespace nsusoft\captcha\adapters\yii;

use nsusoft\captcha\psr\http\message\Request;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use yii\httpclient\Client as YiiClient;

class Client implements RequestFactoryInterface, ClientInterface
{
    /**
     * @inheritDoc
     */
    public function createRequest(string $method, $uri): RequestInterface
    {
        return (new Request())->withMethod($method)->withUri($uri);
    }

    /**
     * @inheritDoc
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $client = new YiiClient();

        // TODO: send request
    }
}