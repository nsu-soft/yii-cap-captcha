<?php


namespace Tests\Cap\Server\Settings;

use GuzzleHttp\Client;
use Tests\Support\CapTester;
use Tests\Support\Data\Cap\Schema;
use Tests\Support\Data\Config\Generator;

class ApikeysGroupTest extends \Codeception\Test\Unit
{
    const API_KEY_NAME = 'api-key-name';

    protected CapTester $tester;

    protected array $headers = [];

    protected ?Client $client = null;

    protected function _before()
    {
        $config = Generator::getCaptchaCredentials();

        $this->headers = [
            'Authorization' => "Bot {$config['apiKey']}",
        ];

        $this->client = new Client([
            'base_uri' => "{$config['server']}:{$config['port']}",
        ]);
    }

    public function testIndex()
    {
        $response = $this->client->get('/server/settings/apikeys', [
            'headers' => $this->headers,
        ]);

        $this->assertEquals(200, $response->getStatusCode(), 'Incorrect status code.');

        $body = $response->getBody();
        $this->assertJson($body, 'Server response is not a JSON.');

        $this->tester->assertJsonSchema(
            Schema::getSchema('/server/settings/apikeys.index.200'),
            json_decode($body)
        );
    }

    public function testCreate()
    {
        $response = $this->client->post('/server/settings/apikeys', [
            'headers' => $this->headers,
            'json' => [
                'name' => self::API_KEY_NAME,
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode(), 'Incorrect status code.');

        $body = $response->getBody();
        $this->assertJson($body, 'Server response is not a JSON.');

        $this->tester->assertJsonSchema(
            Schema::getSchema('/server/settings/apikeys.post.200'),
            json_decode($body)
        );

        // destruct
        $this->deleteLastKey(self::API_KEY_NAME);
    }

    public function testDelete()
    {
        // construct
        $key = $this->createKey();

        // test
        $response = $this->client->delete("/server/settings/apikeys/{$key->id}", [
            'headers' => $this->headers,
        ]);

        $this->assertEquals(200, $response->getStatusCode(), 'Incorrect status code.');

        $body = $response->getBody();
        $this->assertJson($body, 'Server response is not a JSON.');

        $this->tester->assertJsonSchema(
            Schema::getSchema('/server/settings/apikeys.delete.200'),
            json_decode($body)
        );
    }

    protected function createKey(): object
    {
        $response = $this->client->post('/server/settings/apikeys', [
            'headers' => $this->headers,
            'json' => [
                'name' => self::API_KEY_NAME,
            ],
        ]);

        $createdKey = json_decode($response->getBody());

        $response = $this->client->get('/server/settings/apikeys', [
            'headers' => $this->headers,
        ]);

        $keys = json_decode($response->getBody());
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

    protected function deleteLastKey(string $name): void
    {
        $response = $this->client->get('/server/settings/apikeys', [
            'headers' => $this->headers,
        ]);

        $keys = json_decode($response->getBody());

        if (empty($keys)) {
            return;
        }

        $lastKey = null;

        foreach ($keys as $key) {
            if ($name == $key->name && (is_null($lastKey) || $lastKey->created < $key->created)) {
                $lastKey = $key;
            }
        }

        if (is_null($lastKey)) {
            return;
        }

        $this->client->delete("/server/settings/apikeys/{$lastKey->id}", [
            'headers' => $this->headers,
        ]);
    }
}
