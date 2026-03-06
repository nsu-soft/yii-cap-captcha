<?php

namespace nsusoft\captcha;

use nsusoft\captcha\integrations\cap\builders\ApiBuilder;
use stdClass;
use yii\base\Component;
use yii\base\InvalidConfigException;

class Cap extends Component
{
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
    public string $siteKey;

    /**
     * @var string Secret key.
     */
    public string $secretKey;

    /**
     * @var string API key.
     */
    public string $apiKey = '';

    /**
     * @var stdClass API 
     */
    private stdClass $api = null;
    
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        $this->initSiteKey();
        $this->initSecretKey();
        $this->initApi();
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
        if (is_null($this->secretKey)) {
            throw new InvalidConfigException("You should specify a secret key before using this component.");
        }
    }

    /**
     * Initialize API object.
     * @return void
     */
    private function initApi(): void
    {
        $builder = new ApiBuilder([
            'siteKey' => $this->siteKey,
            'secretKey' => $this->secretKey,
            'apiKey' => $this->apiKey,
            'server' => $this->server,
            'port' => $this->port,
        ]);

        $this->api = $builder->build();
    }

    /**
     * @see http://localhost:3000/swagger#tag/challenges/POST/{siteKey}/challenge
     * @param string $siteKey
     * @return stdClass
     */
    public function challenge(string $siteKey): stdClass
    {
        return $this->api->main->challenge($siteKey);
    }

    /**
     * @see http://localhost:3000/swagger#tag/challenges/POST/{siteKey}/redeem
     * @param string $siteKey
     * @param array $data
     * @return stdClass
     */
    public function redeem(string $siteKey, array $data): stdClass
    {
        return $this->api->main->redeem($siteKey, $data);
    }

    /**
     * @see http://localhost:3000/swagger#tag/challenges/POST/{siteKey}/siteverify
     * @param string $siteKey
     * @param array $data
     * @return stdClass
     */
    public function siteverify(string $siteKey, array $data): stdClass
    {
        return $this->api->main->siteverify($siteKey, $data);
    }

    /**
     * @see http://localhost:3000/swagger#GET/server/about
     * @return stdClass
     */
    public function getAbout(): stdClass
    {
        return $this->api->server->main->about();
    }

    /**
     * @see http://localhost:3000/swagger#tag/settings/POST/server/logout
     * @param string $session
     * @return stdClass
     */
    public function logout(string $session): stdClass
    {
        return $this->api->server->main->logout($session);
    }

    /**
     * @see http://localhost:3000/swagger#tag/keys/GET/server/keys
     * @return array
     */
    public function getKeys(): array
    {
        return $this->api->server->keys->main->index();
    }

    /**
     * @see http://localhost:3000/swagger#tag/keys/POST/server/keys
     * @param string $name
     * @return stdClass
     */
    public function createKey(string $name): stdClass
    {
        return $this->api->server->keys->main->create($name);
    }

    /**
     * @see http://localhost:3000/swagger#tag/keys/GET/server/keys/{siteKey}
     * @param string $siteKey
     * @return stdClass
     */
    public function viewKey(string $siteKey): stdClass
    {
        return $this->api->server->keys->main->view($siteKey);
    }

    /**
     * @see http://localhost:3000/swagger#tag/keys/DELETE/server/keys/{siteKey}
     * @param string $siteKey
     * @return stdClass
     */
    public function deleteKey(string $siteKey): stdClass
    {
        return $this->api->server->keys->main->delete($siteKey);
    }

    /**
     * @see http://localhost:3000/swagger#tag/keys/PUT/server/keys/{siteKey}/config
     * @param string $siteKey
     * @param array $options
     * @return stdClass
     */
    public function configKey(string $siteKey, array $options = []): stdClass
    {
        return $this->api->server->keys->main->config($siteKey, $options);
    }

    /**
     * @see http://localhost:3000/swagger#tag/keys/POST/server/keys/{siteKey}/rotate-secret
     * @param string $siteKey
     * @return stdClass
     */
    public function rotateSecret(string $siteKey): stdClass
    {
        return $this->api->server->keys->main->rotateSecret($siteKey);
    }

    /**
     * @see http://localhost:3000/swagger#tag/settings/GET/server/settings/apikeys
     * @return array
     */
    public function getApiKeys(): array
    {
        return $this->api->server->settings->apikeys->index();
    }
    
    /**
     * @see http://localhost:3000/swagger#tag/settings/POST/server/settings/apikeys
     * @param string $name
     * @return stdClass
     */
    public function createApiKey(string $name): stdClass
    {
        return $this->api->server->settings->apikeys->create($name);
    }

    /**
     * @see http://localhost:3000/swagger#tag/settings/DELETE/server/settings/apikeys/{id}
     * @param string $id
     * @return stdClass
     */
    public function deleteApiKey(string $id): stdClass
    {
        return $this->api->server->settings->apikeys->delete($id);
    }

    /**
     * Deletes a last API key by name.
     * @param string $name
     * @return stdClass
     */
    public function deleteLastApiKey(string $name): stdClass
    {
        return $this->api->server->settings->apikeys->deleteLast($name);
    }

    /**
     * @see http://localhost:3000/swagger#tag/settings/GET/server/settings/sessions
     * @return array
     */
    public function getSessions(): array
    {
        return $this->api->server->settings->main->sessions();
    }
}