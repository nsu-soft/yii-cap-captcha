<?php


namespace Tests\Cap\Server\Settings;

use GuzzleHttp\Client;
use Tests\Support\CapTester;
use Tests\Support\Data\Cap\Schema;
use Tests\Support\Data\Config\Generator;

class SettingsGroupTest extends \Codeception\Test\Unit
{
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

    public function testSessions()
    {
        $response = $this->client->get('/server/settings/sessions', [
            'headers' => $this->headers,
        ]);

        $this->assertEquals(200, $response->getStatusCode(), 'Incorrect status code.');

        $body = $response->getBody();
        $this->assertJson($body, 'Server response is not a JSON.');

        $this->tester->assertJsonSchema(
            Schema::getSchema('/server/settings/sessions.200'),
            json_decode($body)
        );
    }
}
