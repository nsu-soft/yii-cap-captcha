<?php


namespace Tests\Unit;

use nsusoft\captcha\Cap;
use nsusoft\captcha\integrations\cap\api\Main;
use nsusoft\captcha\integrations\cap\api\server\keys\Keys;
use nsusoft\captcha\integrations\cap\api\server\Server;
use nsusoft\captcha\integrations\cap\api\server\settings\Apikeys;
use nsusoft\captcha\integrations\cap\api\server\settings\Settings;
use nsusoft\captcha\integrations\cap\builders\ApiBuilder;
use stdClass;
use Tests\Support\Data\Config\Generator;
use Tests\Support\UnitTester;
use Yii;

class CapTest extends \Codeception\Test\Unit
{
    const API_KEY_ID = 'api-key-id';
    const API_KEY_NAME = 'api-key-name';
    const SESSION = 'session';
    const SITE_KEY = 'site-key';
    const SITE_KEY_NAME = 'site-key-name';

    protected UnitTester $tester;

    protected ?Cap $component = null;

    protected function _before()
    {
        $config = Generator::getConfig();
        $this->component = Yii::createObject($config);
        $this->component->setApi($this->getApi());
    }

    public function testChallenge()
    {
        $response = $this->component->challenge(self::SITE_KEY);
        $this->assertInstanceOf(stdClass::class, $response);
    }

    public function testRedeem()
    {
        $response = $this->component->redeem(self::SITE_KEY, []);
        $this->assertInstanceOf(stdClass::class, $response);
    }

    public function testSiteVerify()
    {
        $response = $this->component->siteVerify(self::SITE_KEY, []);
        $this->assertInstanceOf(stdClass::class, $response);
    }

    public function testGetAbout()
    {
        $response = $this->component->getAbout();
        $this->assertInstanceOf(stdClass::class, $response);
    }

    public function testLogout()
    {
        $response = $this->component->logout(self::SESSION);
        $this->assertNull($response);
    }

    public function testGetKeys()
    {
        $response = $this->component->getKeys();
        $this->assertIsArray($response);
    }

    public function testCreateKey()
    {
        $response = $this->component->createKey(self::SITE_KEY_NAME);
        $this->assertInstanceOf(stdClass::class, $response);
    }

    public function testViewKey()
    {
        $response = $this->component->viewKey(self::SITE_KEY);
        $this->assertInstanceOf(stdClass::class, $response);
    }

    public function testDeleteKey()
    {
        $response = $this->component->deleteKey(self::SITE_KEY);
        $this->assertInstanceOf(stdClass::class, $response);
    }

    public function testConfigKey()
    {
        $response = $this->component->configKey(self::SITE_KEY, []);
        $this->assertInstanceOf(stdClass::class, $response);
    }

    public function testRotateSecret()
    {
        $response = $this->component->rotateSecret(self::SITE_KEY);
        $this->assertInstanceOf(stdClass::class, $response);
    }

    public function testGetApiKeys()
    {
        $response = $this->component->getApiKeys();
        $this->assertIsArray($response);
    }

    public function testCreateApiKey()
    {
        $response = $this->component->createApiKey(self::API_KEY_NAME);
        $this->assertInstanceOf(stdClass::class, $response);
    }

    public function testDeleteApiKey()
    {
        $response = $this->component->deleteApiKey(self::API_KEY_ID);
        $this->assertInstanceOf(stdClass::class, $response);
    }

    public function testDeleteLastApiKey()
    {
        $response = $this->component->deleteLastApiKey(self::API_KEY_NAME);
        $this->assertInstanceOf(stdClass::class, $response);
    }

    public function testGetSessions()
    {
        $response = $this->component->getSessions();
        $this->assertIsArray($response);
    }

    protected function getApi(): stdClass
    {
        $builder = new ApiBuilder(['server' => '']);
        $api = $builder->build();

        $api->main = $this->make(Main::class, [
            'challenge' => new stdClass(),
            'redeem' => new stdClass(),
            'siteverify' => new stdClass(),
        ]);
                
        $api->server->main = $this->make(Server::class, [
            'about' => new stdClass(),
            'logout' => null,
        ]);
        
        $api->server->keys->main = $this->make(Keys::class, [
            'index' => [],
            'create' => new stdClass(),
            'view' => new stdClass(),
            'delete' => new stdClass(),
            'config' => new stdClass(),
            'rotateSecret' => new stdClass(),
        ]);

        $api->server->settings->main = $this->make(Settings::class, [
            'sessions' => [],
        ]);

        $api->server->settings->apikeys = $this->make(Apikeys::class, [
            'index' => [],
            'create' => new stdClass(),
            'delete' => new stdClass(),
            'deleteLast' => new stdClass(),
        ]);

        return $api;
    }
}
