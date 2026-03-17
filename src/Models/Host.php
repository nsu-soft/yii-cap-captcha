<?php

namespace NsuSoft\Captcha\Models;

use yii\base\Component;

class Host extends Component
{
    /**
     * @var string Scheme, user, password, and host parts of URI.
     */
    public string $server = '';

    /**
     * @var int|null A port part of URI.
     */
    public ?int $port = null;

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        $this->initServer();
    }

    /**
     * Initialize server URI.
     * @return void
     */
    public function initServer(): void
    {
        $this->server = rtrim($this->server, '/');
    }

    /**
     * Gets base URI of host.
     * @return string
     */
    public function getBaseUri(): string
    {
        if (is_null($this->port)) {
            return $this->server;
        }

        return "{$this->server}:{$this->port}";
    }
}