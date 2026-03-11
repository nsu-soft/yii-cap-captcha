<?php

namespace nsusoft\captcha\adapters\yii;

use Http\Discovery\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use yii\httpclient\Response;

class ResponseAdapter implements ResponseAdapterInterface
{
    /**
     * Converts Yii HTTP client response to PSR-7 format.
     * @param Response $response
     * @return ResponseInterface
     */
    public function toPsr(Response $response): ResponseInterface
    {
        $factory = new Psr17Factory();

        $psr = $factory->createResponse($response->getStatusCode())
            ->withBody($factory->createStream($response->getContent()));

        foreach ($response->getHeaders() as $name => $value) {
            if (!in_array($name, $this->ignoredHeaders())) {
                $psr = $psr->withHeader($name, $value);
            }
        }

        return $psr;
    }

    /**
     * Returns headers which should be ignored when converting formats.
     * @return array
     */
    private function ignoredHeaders(): array
    {
        return ['http-code'];
    }
}