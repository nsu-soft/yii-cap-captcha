<?php

namespace NsuSoft\Captcha;

use NsuSoft\Captcha\Integrations\Cap\Builders\ApiBuilder;
use stdClass;
use yii\base\Component;

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
     * @var string|null Site key.
     */
    public ?string $siteKey = null;

    /**
     * @var string|null Secret key.
     */
    public ?string $secretKey = null;

    /**
     * @var string|null API key.
     */
    public ?string $apiKey = null;

    /**
     * @var stdClass API 
     */
    private stdClass $api;
    
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        $this->initApi();
    }

    /**
     * Initialize API object.
     * @return void
     */
    private function initApi(): void
    {
        $builder = new ApiBuilder([
            'server' => $this->server,
            'port' => $this->port,
            'apiKey' => $this->apiKey,
        ]);

        $this->setApi($builder->build());
    }

    /**
     * Sets an API object.
     * @param stdClass $api
     * @return void
     */
    public function setApi(stdClass $api): void
    {
        $this->api = $api;
    }

    /**
     * @see http://localhost:3000/swagger#tag/challenges/POST/{siteKey}/challenge
     * @return stdClass
     */
    public function challenge(): stdClass
    {
        return $this->api->main->challenge($this->siteKey);
    }

    /**
     * @see http://localhost:3000/swagger#tag/challenges/POST/{siteKey}/redeem
     * @param string $token
     * @param array $solutions
     * @return stdClass
     */
    public function redeem(string $token, array $solutions): stdClass
    {
        return $this->api->main->redeem($this->siteKey, [
            'token' => $token,
            'solutions' => $solutions,
        ]);
    }

    /**
     * @see http://localhost:3000/swagger#tag/challenges/POST/{siteKey}/siteverify
     * @param string $response
     * @return stdClass
     */
    public function siteVerify(string $response): stdClass
    {
        return $this->api->main->siteverify($this->siteKey, [
            'secret' => $this->secretKey,
            'response' => $response,
        ]);
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
     * @return null
     */
    public function logout(string $session): null
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