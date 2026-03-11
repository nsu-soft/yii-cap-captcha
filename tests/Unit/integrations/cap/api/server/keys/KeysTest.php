<?php


namespace Tests\Unit\integrations\cap\api\server\keys;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use nsusoft\captcha\integrations\cap\api\server\keys\Keys;
use Tests\Support\Data\Cap\Schema;
use Tests\Support\Data\Config\Generator;
use Tests\Support\UnitTester;

class KeysTest extends \Codeception\Test\Unit
{
    const SITE_KEY_NAME = 'site-key-name';
    const SITE_KEY = 'a123456789';

    protected UnitTester $tester;

    protected ?Keys $api = null;

    protected function _before()
    {
        $config = Generator::getCaptchaCredentials();

        $this->api = new Keys([
            'server' => $config['server'],
            'port' => $config['port'],
            'siteKey' => $config['siteKey'],
            'secretKey' => $config['secretKey'],
            'apiKey' => $config['apiKey'],
            'factory' => new HttpFactory(),
            'client' => new Client(),
        ]);
    }

    public function testIndex()
    {
        // construct
        $client = $this->make(Client::class, [
            'sendRequest' => Schema::generateResponse('/server/keys/index.200', new HttpFactory()),
        ]);

        $this->api->setClient($client);

        // test
        $keys = $this->api->index();

        $this->assertIsArray($keys);
        $this->tester->assertJsonSchema(Schema::getSchema('/server/keys/index.200'), $keys);
    }

    public function testCreate()
    {
        // construct
        $client = $this->make(Client::class, [
            'sendRequest' => Schema::generateResponse('/server/keys/post.200', new HttpFactory()),
        ]);
        
        $this->api->setClient($client);

        // test
        $key = $this->api->create(self::SITE_KEY_NAME);

        $this->assertIsObject($key);
        $this->tester->assertJsonSchema(Schema::getSchema('/server/keys/post.200'), $key);
    }

    public function testView()
    {
        // construct
        $client = $this->make(Client::class, [
            'sendRequest' => Schema::generateResponse('/server/keys/get.200', new HttpFactory()),
        ]);
        
        $this->api->setClient($client);

        // test
        $response = $this->api->view(self::SITE_KEY);

        $this->assertIsObject($response);
        $this->tester->assertJsonSchema(Schema::getSchema('/server/keys/get.200'), $response);
    }

    public function testDelete()
    {
        // construct
        $client = $this->make(Client::class, [
            'sendRequest' => Schema::generateResponse('/server/keys/delete.200', new HttpFactory()),
        ]);
        
        $this->api->setClient($client);

        // test
        $response = $this->api->delete(self::SITE_KEY);

        $this->assertIsObject($response);
        $this->tester->assertJsonSchema(Schema::getSchema('/server/keys/delete.200'), $response);
    }

    public function testConfig()
    {
        // construct
        $client = $this->make(Client::class, [
            'sendRequest' => Schema::generateResponse('/server/keys/config.200', new HttpFactory()),
        ]);
        
        $this->api->setClient($client);

        // test
        $response = $this->api->config(self::SITE_KEY, [
            'challengeCount' => 10,
            'difficulty' => 5,
            'name' => 'new-' . self::SITE_KEY_NAME,
            'saltSize' => 40,
        ]);

        $this->assertIsObject($response);
        $this->tester->assertJsonSchema(Schema::getSchema('/server/keys/config.200'), $response);
    }

    public function testRotateSecret()
    {
        // construct
        $client = $this->make(Client::class, [
            'sendRequest' => Schema::generateResponse('/server/keys/rotate-secret.200', new HttpFactory()),
        ]);
        
        $this->api->setClient($client);

        // test
        $response = $this->api->rotateSecret(self::SITE_KEY);

        $this->assertIsObject($response);
        $this->tester->assertJsonSchema(Schema::getSchema('/server/keys/rotate-secret.200'), $response);
    }
}
