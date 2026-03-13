<?php

namespace NsuSoft\Captcha\Adapters\Yii;

use Psr\Http\Message\RequestInterface;

class HeadersAdapter
{
    /**
     * Converts PSR-7 headers to Yii HTTP client format.
     * @param RequestInterface $request
     * @return array
     */
    public static function toYii(RequestInterface $request): array
    {
        $headers = [];

        foreach (array_keys($request->getHeaders()) as $key) {
            $headers[$key] = $request->getHeaderLine($key);
        }

        return $headers;
    }
}