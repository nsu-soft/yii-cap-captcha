<?php

namespace NsuSoft\Captcha\Adapters\Yii;

use Psr\Http\Message\RequestInterface;
use yii\httpclient\Client;
use yii\httpclient\Request;

class RequestAdapter implements RequestAdapterInterface
{
    /**
     * @inheritDoc
     */
    public function toYii(RequestInterface $request): Request
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