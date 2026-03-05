<?php

namespace nsusoft\captcha\factories;

use Http\Discovery\Psr18Client;
use nsusoft\captcha\adapters\yii;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use yii\httpclient\Client as YiiClient;

class ClientFactory
{
    /**
     * Creates PSR-18 client, that implements also all factories interfaces.
     * @return ClientInterface|RequestFactoryInterface|ResponseFactoryInterface|ServerRequestFactoryInterface|StreamFactoryInterface|UploadedFileFactoryInterface|UriFactoryInterface
     */
    public static function create(): ClientInterface|RequestFactoryInterface|ResponseFactoryInterface|ServerRequestFactoryInterface|StreamFactoryInterface|UploadedFileFactoryInterface|UriFactoryInterface
    {
        if (class_exists(YiiClient::class)) {
            return new yii\Client();
        }

        return new Psr18Client();
    }
}