<?php


namespace Tests\Unit\Integrations\Cap\Api\Server\Settings;

use Codeception\Stub;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use NsuSoft\Captcha\Integrations\Cap\Api\Server\Settings\Apikeys;
use Tests\Support\Data\Cap\Schema;
use Tests\Support\Data\Config\Generator;
use Tests\Support\UnitTester;

class ApikeysTest extends \Codeception\Test\Unit
{
    const API_KEY_NAME = 'api-key-name';
    const API_KEY_ID = 'api-key-id';

    protected UnitTester $tester;

    protected ?Apikeys $api = null;

    protected function _before()
    {
        $config = Generator::getCaptchaCredentials();

        $this->api = new Apikeys([
            'server' => $config['server'],
            'port' => $config['port'],
            'apiKey' => $config['apiKey'],
            'factory' => new HttpFactory(),
            'client' => new Client(),
        ]);
    }

    public function testIndex()
    {
        // construct
        $client = $this->make(Client::class, [
            'sendRequest' => Schema::generateResponse('/Server/Settings/apikeys.index.200', new HttpFactory()),
        ]);

        $this->api->setClient($client);

        // test
        $keys = $this->api->index();

        $this->assertIsArray($keys);
        $this->tester->assertJsonSchema(Schema::getSchema('/Server/Settings/apikeys.index.200'), $keys);
    }

    public function testCreate()
    {
        // construct
        $client = $this->make(Client::class, [
            'sendRequest' => Schema::generateResponse('/Server/Settings/apikeys.post.200', new HttpFactory()),
        ]);

        $this->api->setClient($client);

        // test
        $key = $this->api->create(self::API_KEY_NAME);

        $this->assertIsObject($key);
        $this->tester->assertJsonSchema(Schema::getSchema('/Server/Settings/apikeys.post.200'), $key);
    }

    public function testDelete()
    {
        // construct
        $client = $this->make(Client::class, [
            'sendRequest' => Schema::generateResponse('/Server/Settings/apikeys.delete.200', new HttpFactory()),
        ]);

        $this->api->setClient($client);

        // test
        $response = $this->api->delete(self::API_KEY_ID);

        $this->tester->assertJsonSchema(Schema::getSchema('/Server/Settings/apikeys.delete.200'), $response);
    }

    public function testDeleteLast()
    {
        // construct
        $client = $this->make(Client::class, [
            'sendRequest' => Stub::consecutive(
                Schema::generateResponse('/Server/Settings/apikeys.index.200', new HttpFactory()),
                Schema::generateResponse('/Server/Settings/apikeys.delete.200', new HttpFactory())
            ),
        ]);

        $this->api->setClient($client);

        // test
        $response = $this->api->deleteLast(self::API_KEY_NAME);

        $this->tester->assertJsonSchema(Schema::getSchema('/Server/Settings/apikeys.delete.200'), $response);
    }
}
