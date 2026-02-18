<?php


namespace Tests\Cap;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use JsonSchema\Validator;
use Tests\Support\CapTester;
use Tests\Support\Data\Cap\Schema;
use Tests\Support\Data\Config\Generator;

class MainGroupTest extends \Codeception\Test\Unit
{
    protected CapTester $tester;

    protected ?Client $client = null;

    protected function _before()
    {
        $this->client = new Client([
            'base_uri' => 'http://cap:3000',
        ]);
    }

    /**
     * @dataProvider endpointsData
     */
    public function testEndpointResponseSuccess(
        string $method,
        string $endpoint, 
        string $schema)
    {
        $config = Generator::getCaptchaCredentials();
    
        $response = $this->client->request($method, "/{$config['siteKey']}/{$endpoint}");
        $this->assertEquals(200, $response->getStatusCode(), 'Incorrect status code.');

        $body = $response->getBody();
        $this->assertJson($body, 'Server response is not a JSON.');

        $json = json_decode($body);

        $validator = new Validator();
        $validator->validate($json, Schema::getSchema($schema));

        $this->assertTrue($validator->isValid(), 'Incorrect JSON schema.');
    }

    /**
     * @dataProvider endpointsData
     */
    public function testEndpointResponseFailed(
        string $method,
        string $endpoint, 
        string $schema)
    {
        $this->expectException(ClientException::class);
        $this->client->request('POST', "/incorrect-site-key/{$endpoint}");
    }

    public static function endpointsData(): array
    {
        return [
            'challenge' => ['POST', 'challenge', '/main/challenge'],
            // 'redeem' => ['POST', 'redeem', '/main/redeem'],
            // 'siteverify' => ['POST', 'siteverify', '/main/siteverify'],
        ];
    }
}
