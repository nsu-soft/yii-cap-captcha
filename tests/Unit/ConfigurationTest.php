<?php

namespace Tests\Unit;

use Codeception\Attribute\Depends;
use Psr\Http\Client\ClientInterface;
use Tests\Support\Data\Config\Generator;
use Tests\Support\UnitTester;
use Yii;
use yii\base\InvalidConfigException;

class ConfigurationTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    protected function _before()
    {
    }

    public function testFileExists()
    {
        $captchaFile = Generator::getCaptchaCredentialsFile();
        $this->assertFileExists($captchaFile, "Captcha credentials file doesn't exist.");
    }

    #[Depends('testFileExists')]
    public function testCorrectYiiClient()
    {
        $this->expectNotToPerformAssertions();
        Yii::createObject(Generator::getYiiClientConfig());
    }

    #[Depends('testFileExists')]
    public function testCorrectGuzzleClient()
    {
        $this->expectNotToPerformAssertions();
        Yii::createObject(Generator::getGuzzleClientConfig());
    }

    #[Depends('testFileExists')]
    public function testCorrectSymfonyClient()
    {
        $this->expectNotToPerformAssertions();
        Yii::createObject(Generator::getSymfonyClientConfig());
    }

    #[Depends('testFileExists')]
    public function testEmptySiteKey()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessageMatches('/site key/');

        $config = Generator::getYiiClientConfig();
        unset($config['siteKey']);

        Yii::createObject($config);
    }

    #[Depends('testFileExists')]
    public function testEmptySecretKey()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessageMatches('/secret key/');

        $config = Generator::getYiiClientConfig();
        unset($config['secretKey']);

        Yii::createObject($config);
    }

    #[Depends('testFileExists')]
    public function testEmptyClient()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessageMatches('/client/');

        $config = Generator::getYiiClientConfig();
        unset($config['client']);

        Yii::createObject($config);
    }

    #[Depends('testCorrectYiiClient')]
    public function testClientWithAdapter()
    {
        $component = Yii::createObject(Generator::getYiiClientConfig());
        $this->assertInstanceOf(ClientInterface::class, $component->client);
    }

    #[Depends('testFileExists')]
    public function testNotPsrClientWithoutAdapter()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessageMatches('/Unknown/');

        $config = Generator::getYiiClientConfig();
        $config['client'] = function () {
            return \Symfony\Component\HttpClient\HttpClient::create();
        };

        Yii::createObject($config);
    }
}
