<?php

namespace nsusoft\captcha\adapters\yii;

use Psr\Http\Message\RequestInterface;
use yii\httpclient\Client;
use yii\httpclient\Request;

class RequestAdapter
{
    /**
     * Converts PSR-7 request to Yii HTTP client request format.
     * @param RequestInterface $request
     * @return Request
     */
    public static function toYii(RequestInterface $request): Request
    {
        $client = new Client();

        return $client->createRequest()
            ->setMethod($request->getMethod())
            ->setHeaders(HeadersAdapter::toYii($request))
            ->setUrl($request->getUri())
            ->setContent((string)$request->getBody())
            ->setOptions(['protocolVersion' => $request->getProtocolVersion()]);
    }
}