<?php

namespace NsuSoft\Captcha\Integrations\Cap\Builders;

use NsuSoft\Captcha\Factories\PsrFactory;
use NsuSoft\Captcha\Integrations\Cap\Api\Main;
use NsuSoft\Captcha\Integrations\Cap\Api\Server\Keys\Keys;
use NsuSoft\Captcha\Integrations\Cap\Api\Server\Server;
use NsuSoft\Captcha\Integrations\Cap\Api\Server\Settings\Apikeys;
use NsuSoft\Captcha\Integrations\Cap\Api\Server\Settings\Settings;
use stdClass;
use yii\base\Component;

class ApiBuilder extends Component
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
     * @var string|null API key.
     */
    public ?string $apiKey = null;

    /**
     * Builds API object.
     * @return stdClass
     */
    public function build(): stdClass
    {
        $api = new stdClass();
        $api->main = new Main($this->getConfig());
        
        $api->server = new stdClass();
        $api->server->main = new Server($this->getConfig());
        
        $api->server->keys = new stdClass();
        $api->server->keys->main = new Keys($this->getConfig());

        $api->server->settings = new stdClass();
        $api->server->settings->main = new Settings($this->getConfig());
        $api->server->settings->apikeys = new Apikeys($this->getConfig());

        return $api;
    }

    /**
     * Configures params for API object.
     * @return array
     */
    private function getConfig(): array
    {
        $config = [
            'server' => $this->server,
            'factory' => PsrFactory::createFactory(),
            'client' => PsrFactory::createClient(),
        ];

        if (isset($this->port)) {
            $config['port'] = $this->port;
        }

        if (isset($this->apiKey)) {
            $config['apiKey'] = $this->apiKey;
        }

        return $config;
    }
}