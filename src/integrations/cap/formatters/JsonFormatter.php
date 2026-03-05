<?php

namespace nsusoft\captcha\integrations\cap\formatters;

use Psr\Http\Message\ResponseInterface;
use yii\base\InvalidArgumentException;
use yii\helpers\Json;

class JsonFormatter
{
    /**
     * Converts ResponseInterface body from JSON string to associative array.
     * @param ResponseInterface $response
     * @return array|object|null
     * @throws InvalidArgumentException
     */
    public static function fromResponse(ResponseInterface $response): array|object|null
    {
        return Json::decode((string)$response->getBody(), false);
    }
}