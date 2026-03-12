<?php

namespace NsuSoft\Captcha\Exceptions;

use Psr\Http\Message\ResponseInterface;
use yii\base\InvalidArgumentException;

/**
 * Exception for when response body is not a valid JSON.
 */
class JsonDecodeException extends InvalidArgumentException implements ResponseExceptionInterface
{
    public function __construct(
        InvalidArgumentException $e,
        protected ResponseInterface $response,
    ) {
        parent::__construct($e->getMessage(), $e->getCode(), $e);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'JSON decode exception';
    }

    /**
     * @inheritDoc
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}