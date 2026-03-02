<?php

namespace nsusoft\captcha\factories;

use Http\Discovery\Psr18Client;
use nsusoft\captcha\adapters\yii;
use Psr\Http\Client\ClientInterface;
use yii\httpclient\Client as YiiClient;

class PsrFactory
{
    /**
     * Creates PSR-18 client.
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