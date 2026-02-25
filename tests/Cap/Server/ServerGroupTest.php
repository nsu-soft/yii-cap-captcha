<?php


namespace Tests\Cap\Server;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use JsonSchema\Validator;
use Tests\Support\CapTester;
use Tests\Support\Data\Cap\Schema;
use Tests\Support\Data\Config\Generator;

class ServerGroupTest extends \Codeception\Test\Unit
{
    const IRRELEVANT_TOKEN = 'eyJ0b2tlbiI6IjJkMTc4MDcwYzRiYWFkNzZlNjc1MDQ3ODE0MzNhNDE3NjkwYzEyN2IxNTM4NTI0YWJjYmExNjI1OTczYyIsImhhc2giOiIkYXJnb24yaWQkdj0xOSRtPTY1NTM2LHQ9MixwPTEkM3F5VnVJRm9oMm44Y280bVlyZmY0ZGVsOEU2QlRQc1l0a3cwZXp6QWtEZyR6VGJtS1lub054blA3eG9qUlMwVmJjMlhlS1RVcjRyYlN6bjFoRERaZ2VvIn0=';

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

    public function testAboutSuccess()
    {
        $response = $this->client->get("/server/about", [
            'headers' => $this->headers,
        ]);

        $this->assertEquals(200, $response->getStatusCode(), 'Incorrect status code.');

        $body = $response->getBody();
        $this->assertJson($body, 'Server response is not a JSON.');

        $json = json_decode($body);

        $validator = new Validator();
        $validator->validate($json, Schema::getSchema('/server/about.200'));

        $this->assertTrue($validator->isValid(), 'Incorrect JSON schema.');
    }

    public function testLogoutAvailable()
    {
        try {
            $this->client->post('/server/logout', [
                'headers' => [
                    'Authorization' => 'Bearer ' . self::IRRELEVANT_TOKEN,
                ],
            ]);

            $this->fail();
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $this->assertEquals(401, $response->getStatusCode(), 'Incorrect status code.');
        }
    }
}
