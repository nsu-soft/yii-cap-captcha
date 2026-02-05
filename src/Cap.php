<?php

namespace nsusoft\captcha;

use Closure;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class Cap extends Component
{
    /**
     * @var yii\httpclient\Client|Psr\Http\Client|Closure|array|null Client for HTTP connection.
     */
    public mixed $client = null;

    /**
     * @var string|null Site key.
     */
    public ?string $siteKey = null;

    /**
     * @var string|null Secret key.
     */
    public ?string $secterKey = null;
    
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
        if (is_null($this->siteKey)) {
            throw new InvalidConfigException("You should specify a site key before using this component.");
        }
    }

    /**
     * Initialize secret key.
     * @return void
     */
    private function initSecretKey(): void
    {
        if (is_null($this->secterKey)) {
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
    }
}