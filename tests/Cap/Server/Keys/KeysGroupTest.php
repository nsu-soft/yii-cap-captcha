<?php


namespace Tests\Cap\Server\Keys;

use GuzzleHttp\Client;
use Tests\Support\CapTester;
use Tests\Support\Data\Cap\Schema;
use Tests\Support\Data\Config\Generator;

class KeysGroupTest extends \Codeception\Test\Unit
{
    const SITE_KEY_NAME = 'site-key-name';

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
        $response = $this->client->get('/server/keys', [
            'headers' => $this->headers,
        ]);

        $this->assertEquals(200, $response->getStatusCode(), 'Incorrect status code.');

        $body = $response->getBody();
        $this->assertJson($body, 'Server response is not a JSON.');

        $this->tester->assertJsonSchema(
            Schema::getSchema('/server/keys/index.200'),
            json_decode($body)
        );
    }

    public function testCreate()
    {
        $response = $this->client->post('/server/keys', [
            'headers' => $this->headers,
            'json' => [
                'name' => self::SITE_KEY_NAME,
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode(), 'Incorrect status code.');

        $body = $response->getBody();
        $this->assertJson($body, 'Server response is not a JSON.');

        $key = json_decode($body);

        try {
            $this->tester->assertJsonSchema(Schema::getSchema('/server/keys/post.200'), $key);
        } finally {
            // destruct
            $this->deleteKey($key);
        }
    }

    public function testView()
    {
        // construct
        $key = $this->createKey();

        // test
        $response = $this->client->get("/server/keys/{$key->siteKey}", [
            'headers' => $this->headers,
        ]);

        $this->assertEquals(200, $response->getStatusCode(), 'Incorrect status code.');

        $body = $response->getBody();
        $this->assertJson($body, 'Server response is not a JSON.');

        try {
            $this->tester->assertJsonSchema(
                Schema::getSchema('/server/keys/get.200'),
                json_decode($body)
            );
        } finally {
            // destruct
            $this->deleteKey($key);
        }
    }

    public function testDelete()
    {
        // construct
        $key = $this->createKey();

        // test
        $response = $this->client->delete("/server/keys/{$key->siteKey}", [
            'headers' => $this->headers,
        ]);

        $this->assertEquals(200, $response->getStatusCode(), 'Incorrect status code.');

        $body = $response->getBody();
        $this->assertJson($body, 'Server response is not a JSON.');

        $this->tester->assertJsonSchema(
            Schema::getSchema('/server/keys/delete.200'),
            json_decode($body)
        );
    }

    public function testConfig()
    {
        // construct
        $key = $this->createKey();

        // test
        $response = $this->client->put("/server/keys/{$key->siteKey}/config", [
            'headers' => $this->headers,
            'json' => [
                'challengeCount' => 10,
                'difficulty' => 5,
                'name' => 'new-' . self::SITE_KEY_NAME,
                'saltSize' => 40,
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode(), 'Incorrect status code.');

        $body = $response->getBody();
        $this->assertJson($body, 'Server response is not a JSON.');

        $this->tester->assertJsonSchema(
            Schema::getSchema('/server/keys/config.200'),
            json_decode($body)
        );

        // destruct
        $this->deleteKey($key);
    }

    public function testRotateSecret()
    {
        // construct
        $key = $this->createKey();

        // test
        $response = $this->client->post("/server/keys/{$key->siteKey}/rotate-secret", [
            'headers' => $this->headers,
        ]);

        $this->assertEquals(200, $response->getStatusCode(), 'Incorrect status code.');

        $body = $response->getBody();
        $this->assertJson($body, 'Server response is not a JSON.');

        $this->tester->assertJsonSchema(
            Schema::getSchema('/server/keys/rotate-secret.200'),
            json_decode($body)
        );

        // destruct
        $this->deleteKey($key);
    }

    protected function createKey(): object
    {
        $response = $this->client->post('/server/keys', [
            'headers' => $this->headers,
            'json' => [
                'name' => self::SITE_KEY_NAME,
            ],
        ]);

        return json_decode($response->getBody());
    }

    protected function deleteKey(object $key): void
    {
        $this->client->delete("/server/keys/{$key->siteKey}", [
            'headers' => $this->headers,
        ]);
    }
}