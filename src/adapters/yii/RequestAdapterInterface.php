<?php

namespace nsusoft\captcha\adapters\yii;

use Psr\Http\Message\RequestInterface;
use yii\httpclient\Request;

interface RequestAdapterInterface
{
    /**
     * Converts PSR-7 request to Yii HTTP client request format.
     * @param RequestInterface $request
     * @return Request
     */
    public function toYii(RequestInterface $request): Request;
}