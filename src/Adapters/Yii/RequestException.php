<?php

namespace NsuSoft\Captcha\Adapters\Yii;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use yii\httpclient\Exception;

class RequestException extends Exception implements RequestExceptionInterface
{
    public function __construct(
        Exception $e,
        private RequestInterface $request,
    ) {
        parent::__construct($e->getMessage(), $e->getCode(), $e);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'Request exception';
    }

    /**
     * @inheritDoc
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}