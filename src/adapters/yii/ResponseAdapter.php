<?php

namespace nsusoft\captcha\adapters\yii;

use Http\Discovery\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use yii\httpclient\Response;

class ResponseAdapter
{
    /**
     * Converts Yii HTTP client response to PSR-7 format.
     * @param Response $response
     * @return ResponseInterface
     */
    public static function toPsr(Response $response): ResponseInterface
    {
        $factory = new Psr17Factory();

        $psr = $factory->createResponse($response->getStatusCode())
            ->withBody($factory->createStream($response->getContent()));

        foreach ($response->getHeaders() as $name => $value) {
            $psr = $psr->withHeader($name, $value);
        }

        return $psr;
    }
}