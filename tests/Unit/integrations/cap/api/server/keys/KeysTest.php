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

    protected UnitTester $tester;

    protected ?Keys $api = null;

    protected function _before()
    {
        $config = Generator::getCaptchaCredentials();

        $this->api = new Keys([
            'siteKey' => $config['siteKey'],
            'secretKey' => $config['secretKey'],
            'apiKey' => $config['apiKey'],
            'server' => $config['server'],
            'port' => $config['port'],
            'factory' => new HttpFactory(),
            'client' => new Client(),
        ]);
    }

    public function testIndex()
    {
        $keys = $this->api->index();

        $this->assertIsArray($keys);
        $this->tester->assertJsonSchema(Schema::getSchema('/server/keys/index.200'), $keys);
    }

    public function testCreate()
    {
        // test
        $key = $this->createKey();

        $this->assertIsObject($key);
        $this->tester->assertJsonSchema(Schema::getSchema('/server/keys/post.200'), $key);

        // destruct
        $this->deleteKey($key);
    }

    public function testView()
    {
        // construct
        $key = $this->createKey();

        // test
        $response = $this->api->view($key->siteKey);

        $this->assertIsObject($response);
        $this->tester->assertJsonSchema(Schema::getSchema('/server/keys/get.200'), $response);

        // destruct
        $this->deleteKey($key);
    }

    public function testDelete()
    {
        // construct
        $key = $this->createKey();

        // test
        $response = $this->deleteKey($key);

        $this->assertIsObject($response);
        $this->tester->assertJsonSchema(Schema::getSchema('/server/keys/delete.200'), $response);
    }

    public function testConfig()
    {
        // construct
        $key = $this->createKey();

        // test
        $response = $this->api->config($key->siteKey, [
            'challengeCount' => 10,
            'difficulty' => 5,
            'name' => 'new-' . self::SITE_KEY_NAME,
            'saltSize' => 40,
        ]);

        $this->assertIsObject($response);
        $this->tester->assertJsonSchema(Schema::getSchema('/server/keys/config.200'), $response);

        // destruct
        $this->deleteKey($key);
    }

    public function testRotateSecret()
    {
        // construct
        $key = $this->createKey();

        // test
        $response = $this->api->rotateSecret($key->siteKey);

        $this->assertIsObject($response);
        $this->tester->assertJsonSchema(Schema::getSchema('/server/keys/rotate-secret.200'), $response);

        // destruct
        $this->deleteKey($key);
    }

    protected function createKey(): object
    {
        return $this->api->create(self::SITE_KEY_NAME);
    }

    protected function deleteKey(object $key): object
    {
        return $this->api->delete($key->siteKey);
    }
}
