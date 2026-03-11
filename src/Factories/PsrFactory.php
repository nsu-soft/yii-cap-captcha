<?php

namespace NsuSoft\Captcha\Factories;

use Http\Discovery\Psr17Factory;
use Http\Discovery\Psr18Client;
use NsuSoft\Captcha\Adapters\Yii;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use yii\httpclient\Client as YiiClient;

class PsrFactory
{
    /**
     * Creates PSR-17 factory, that implements also all factories interfaces.
     * @return RequestFactoryInterface|ResponseFactoryInterface|ServerRequestFactoryInterface|StreamFactoryInterface|UploadedFileFactoryInterface|UriFactoryInterface
     */
    public static function createFactory(): RequestFactoryInterface|ResponseFactoryInterface|ServerRequestFactoryInterface|StreamFactoryInterface|UploadedFileFactoryInterface|UriFactoryInterface
    {
        return new Psr17Factory();
    }

    /**
     * Creates PSR-18 HTTP client.
     * @return ClientInterface
     */
    public static function createClient(): ClientInterface
    {
        if (class_exists(YiiClient::class)) {
            return new yii\Client();
        }

        return new Psr18Client();
    }
}