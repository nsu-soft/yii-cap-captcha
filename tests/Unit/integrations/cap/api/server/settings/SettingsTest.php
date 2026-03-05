<?php


namespace Tests\Unit\integrations\cap\api\server\settings;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use nsusoft\captcha\integrations\cap\api\server\settings\Settings;
use Tests\Support\Data\Cap\Schema;
use Tests\Support\Data\Config\Generator;
use Tests\Support\UnitTester;

class SettingsTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    protected ?Settings $api = null;

    protected function _before()
    {
        $config = Generator::getCaptchaCredentials();

        $this->api = new Settings([
            'siteKey' => $config['siteKey'],
            'secretKey' => $config['secretKey'],
            'apiKey' => $config['apiKey'],
            'server' => $config['server'],
            'port' => $config['port'],
            'factory' => new HttpFactory(),
            'client' => new Client(),
        ]);
    }

    public function testSessions()
    {
        $sessions = $this->api->sessions();

        $this->assertIsArray($sessions);
        $this->tester->assertJsonSchema(Schema::getSchema('/server/settings/sessions.200'), $sessions);
    }
}
