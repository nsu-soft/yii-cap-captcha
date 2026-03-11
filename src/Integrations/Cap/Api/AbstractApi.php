<?php

namespace NsuSoft\Captcha\Integrations\Cap\Api;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use yii\base\Component;

abstract class AbstractApi extends Component
{
    /**
     * @var string URL of Cap Captcha server.
     */
    public string $server;

    /**
     * @var int|null Port of Cap Captcha server.
     */
    public ?int $port = null;

    /**
     * @var string Site key.
     */
    public string $siteKey = '';

    /**
     * @var string Secret key.
     */
    public string $secretKey = '';

    /**
     * @var string API key.
     */
    public string $apiKey = '';

    /**
     * @var RequestFactoryInterface|ResponseFactoryInterface|ServerRequestFactoryInterface|StreamFactoryInterface|UploadedFileFactoryInterface|UriFactoryInterface PSR-17 factory.
     */
    protected RequestFactoryInterface|ResponseFactoryInterface|ServerRequestFactoryInterface|StreamFactoryInterface|UploadedFileFactoryInterface|UriFactoryInterface $factory;

    /**
     * @var ClientInterface HTTP client.
     */
    protected ClientInterface $client;

    /**
     * Set PSR-17 factory.
     * @param RequestFactoryInterface|ResponseFactoryInterface|ServerRequestFactoryInterface|StreamFactoryInterface|UploadedFileFactoryInterface|UriFactoryInterface $factory
     * @return void
     */
    public function setFactory(RequestFactoryInterface|ResponseFactoryInterface|ServerRequestFactoryInterface|StreamFactoryInterface|UploadedFileFactoryInterface|UriFactoryInterface $factory): void
    {
        $this->factory = $factory;
    }

    /**
     * Set PSR-18 client.
     * @param ClientInterface $client
     * @return void
     */
    public function setClient(ClientInterface $client): void
    {
        $this->client = $client;
    }

    /**
     * Gets base URI of Cap server.
     * @return string
     */
    protected function getBaseUri(): string
    {
        if (is_null($this->port)) {
            return $this->server;
        }

        return "{$this->server}:{$this->port}";
    }

    /**
     * Gets a value of Authorization header.
     * @return string
     */
    protected function getAuthorizationHeader(): string
    {
        return "Bot {$this->apiKey}";
    }
}