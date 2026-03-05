<?php

namespace nsusoft\captcha\integrations\cap\formatters;

use Psr\Http\Message\ResponseInterface;
use yii\helpers\Json;

class JsonFormatter
{
    /**
     * Converts ResponseInterface body from JSON string to associative array.
     */
    public static function fromResponse(ResponseInterface $response): array
    {
        return Json::decode((string)$response->getBody());
    }
}