<?php

namespace NsuSoft\Captcha\Integrations\Cap\Formatters;

use Psr\Http\Message\ResponseInterface;
use stdClass;
use yii\base\InvalidArgumentException;
use yii\helpers\Json;

class JsonFormatter
{
    /**
     * Converts ResponseInterface body from JSON string to associative array.
     * @param ResponseInterface $response
     * @return array|stdClass|null
     * @throws InvalidArgumentException
     */
    public static function fromResponse(ResponseInterface $response): array|stdClass|null
    {
        return Json::decode((string)$response->getBody(), false);
    }
}