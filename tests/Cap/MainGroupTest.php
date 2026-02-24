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

    protected array $config = [];

    protected ?Client $client = null;

    protected function _before()
    {
        $this->config = Generator::getCaptchaCredentials();

        $this->client = new Client([
            'base_uri' => "{$this->config['server']}:{$this->config['port']}",
        ]);
    }

    public function testChallengeSuccess()
    {
        $response = $this->client->post("/{$this->config['siteKey']}/challenge");
        $this->assertEquals(200, $response->getStatusCode(), 'Incorrect status code.');

        $body = $response->getBody();
        $this->assertJson($body, 'Server response is not a JSON.');

        $json = json_decode($body);

        $validator = new Validator();
        $validator->validate($json, Schema::getSchema('/main/challenge.200'));

        $this->assertTrue($validator->isValid(), 'Incorrect JSON schema.');
    }

    public function testChallengeFailed()
    {
        $this->expectException(ClientException::class);
        $this->client->post("/incorrect-site-key/challenge");
    }

    public function testRedeemAvailable()
    {
        $response = $this->client->post("/{$this->config['siteKey']}/challenge");
        $json = json_decode($response->getBody());

        try {
            $this->client->post("/{$this->config['siteKey']}/redeem", [
                'form_params' => [
                    'token' => $json->token,
                    'solutions' => ['invalid-solution'],
                ],
            ]);

            $this->fail();
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $this->assertEquals(403, $response->getStatusCode(), 'Incorrect status code.');
        }
    }

    public function testSiteverifyAvailable()
    {
        try {
            $this->client->post("/{$this->config['siteKey']}/siteverify", [
                'form_params' => [
                    'secret' => $this->config['secretKey'],
                    'response' => 'invalid-token',
                ],
            ]);

            $this->fail();
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $this->assertEquals(404, $response->getStatusCode(), 'Incorrect status code.');
        }
    }
}
