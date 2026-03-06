<?php


namespace Tests\Unit\integrations\cap\api\server\settings;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use nsusoft\captcha\integrations\cap\api\server\settings\Apikeys;
use Tests\Support\Data\Cap\Schema;
use Tests\Support\Data\Config\Generator;
use Tests\Support\UnitTester;

class ApikeysTest extends \Codeception\Test\Unit
{
    const API_KEY_NAME = 'api-key-name';

    protected UnitTester $tester;

    protected ?Apikeys $api = null;

    protected function _before()
    {
        $config = Generator::getCaptchaCredentials();

        $this->api = new Apikeys([
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
        $keys = $this->api->index();

        $this->assertIsArray($keys);
        $this->tester->assertJsonSchema(Schema::getSchema('/server/settings/apikeys.index.200'), $keys);
    }

    public function testCreate()
    {
        $key = $this->api->create(self::API_KEY_NAME);

        $this->assertIsObject($key);
        $this->tester->assertJsonSchema(Schema::getSchema('/server/settings/apikeys.post.200'), $key);

        // destruct
        $this->api->deleteLast(self::API_KEY_NAME);
    }

    public function testDelete()
    {
        // construct
        $key = $this->createKey();

        // test
        $response = $this->api->delete($key->id);

        $this->tester->assertJsonSchema(Schema::getSchema('/server/settings/apikeys.delete.200'), $response);
    }

    public function testDeleteLast()
    {
        // construct
        $key = $this->createKey();

        // test
        $response = $this->api->deleteLast($key->name);

        $this->tester->assertJsonSchema(Schema::getSchema('/server/settings/apikeys.delete.200'), $response);
    }

    protected function createKey(): object
    {
        $createdKey = $this->api->create(self::API_KEY_NAME);
        $keys = $this->api->index();
        $lastKey = null;

        foreach ($keys as $key) {
            if (self::API_KEY_NAME == $key->name && (is_null($lastKey) || $lastKey->created < $key->created)) {
                $lastKey = $key;
            }
        }

        $createdKey->name = $lastKey->name;
        $createdKey->id = $lastKey->id;
        $createdKey->created = $lastKey->created;

        return $createdKey;
    }
}
