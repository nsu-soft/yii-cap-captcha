<?php

namespace NsuSoft\Captcha\Exceptions;

use Psr\Http\Message\ResponseInterface;

interface ResponseExceptionInterface extends ServerExceptionInterface
{
    /**
     * Returns the response.
     *
     * The response object MAY be a different object from the one returned from ClientInterface::sendRequest()
     *
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface;
}