<?php

namespace nsusoft\captcha\adapters;

use Psr\Http\Client\ClientInterface;
use yii\base\InvalidConfigException;

class ClientAdapterFactory
{
    /**
     * Wrap the client with an adapter that supports ClientInterface.
     * 
     * @param mixed $client Client object
     * @return ClientInterface
     * @throws InvalidConfigException
     */
    public static function wrap(mixed $client): ClientInterface
    {
        if ('yii\httpclient\Client' === get_class($client)) {
            return new YiiClientAdapter(['client' => $client]);
        }

        throw new InvalidConfigException("Unknown HTTP-client type.");
    }
}