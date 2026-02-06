<?php

namespace nsusoft\captcha\adapters;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use yii\base\Component;
use yii\httpclient\Client;

class YiiClientAdapter extends Component implements ClientInterface
{
    /**
     * @var Client Yii HTTP client object.
     */
    public Client $client;

    /**
     * @inheritDoc
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        
    }
}