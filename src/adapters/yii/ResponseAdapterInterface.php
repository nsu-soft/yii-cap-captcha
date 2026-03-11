<?php

namespace nsusoft\captcha\adapters\yii;

use Psr\Http\Message\ResponseInterface;
use yii\httpclient\Response;

interface ResponseAdapterInterface
{
    /**
     * Converts Yii HTTP client response to PSR-7 format.
     * @param Response $response
     * @return ResponseInterface
     */
    public function toPsr(Response $response): ResponseInterface;
}