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

    public function testChallengeSuccess()
    {
        $config = Generator::getCaptchaCredentials();
    
        $response = $this->client->post("/{$config['siteKey']}/challenge");
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
        $config = Generator::getCaptchaCredentials();

        $response = $this->client->post("/{$config['siteKey']}/challenge");
        $json = json_decode($response->getBody());

        try {
            $this->client->post("/{$config['siteKey']}/redeem", [
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
        $config = Generator::getCaptchaCredentials();

        try {
            $this->client->post("/{$config['siteKey']}/siteverify", [
                'form_params' => [
                    'secret' => $config['secretKey'],
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
