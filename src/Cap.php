<?php

namespace nsusoft\captcha;

use Closure;
use nsusoft\captcha\adapters\ClientAdapterFactory;
use Psr\Http\Client\ClientInterface;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class Cap extends Component
{
    /**
     * @var ClientInterface|Closure|array|null Client for HTTP connection.
     */
    public mixed $client = null;

    /**
     * @var string URL of Cap Captcha server.
     */
    public string $server = 'http://localhost';

    /**
     * @var int|null Port of Cap Captcha server.
     */
    public ?int $port = 3000;

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
     * @inheritDoc
     */
    public function init(): void
    {
        $this->initSiteKey();
        $this->initSecretKey();
        $this->initClient();
    }

    /**
     * Initialize site key.
     * @return void
     */
    private function initSiteKey(): void
    {
        if (empty($this->siteKey)) {
            throw new InvalidConfigException("You should specify a site key before using this component.");
        }
    }

    /**
     * Initialize secret key.
     * @return void
     */
    private function initSecretKey(): void
    {
        if (empty($this->secretKey)) {
            throw new InvalidConfigException("You should specify a secret key before using this component.");
        }
    }

    /**
     * Initialize HTTP-client.
     * @return void
     */
    private function initClient(): void
    {
        if (is_null($this->client)) {
            throw new InvalidConfigException("You should specify a client options before using this component.");
        }

        if (is_array($this->client) || $this->client instanceof Closure) {
            $this->client = Yii::createObject($this->client);
        }

        if (!($this->client instanceof ClientInterface)) {
            $this->client = ClientAdapterFactory::wrap($this->client);
        }
    }
}