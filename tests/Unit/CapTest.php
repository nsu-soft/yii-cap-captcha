<?php

namespace Tests\Unit;

use Codeception\Attribute\Depends;
use GuzzleHttp\Client as GuzzleClient;
use nsusoft\captcha\Cap;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\HttpClient\Psr18Client as SymfonyClient;
use Tests\Support\UnitTester;
use Yii;
use yii\base\InvalidConfigException;
use yii\httpclient\Client as YiiClient;

class CapTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    protected function _before()
    {
    }

    public function testConfigurationFileExists()
    {
        $captchaFile = $this->getConfigFileName();
        $this->assertFileExists($captchaFile, "Captcha configuration file doesn't exist.");
    }

    #[Depends('testConfigurationFileExists')]
    public function testCorrectYiiClientConfiguration()
    {
        $this->expectNotToPerformAssertions();
        Yii::createObject($this->getYiiClientConfig());
    }

    #[Depends('testConfigurationFileExists')]
    public function testCorrectGuzzleClientConfiguration()
    {
        $this->expectNotToPerformAssertions();
        Yii::createObject($this->getGuzzleClientConfig());
    }

    #[Depends('testConfigurationFileExists')]
    public function testCorrectSymfonyClientConfiguration()
    {
        $this->expectNotToPerformAssertions();
        Yii::createObject($this->getSymfonyClientConfig());
    }

    #[Depends('testConfigurationFileExists')]
    public function testEmptySiteKeyConfiguration()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessageMatches('/site key/');

        $captcha = $this->getCaptchaConfig();

        $config = [
            'class' => Cap::class,
            'secretKey' => $captcha['secretKey'],
            'client' => [
                'class' => YiiClient::class,
            ],
        ];

        Yii::createObject($config);
    }

    #[Depends('testConfigurationFileExists')]
    public function testEmptySecretKeyConfiguration()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessageMatches('/secret key/');

        $captcha = $this->getCaptchaConfig();

        $config = [
            'class' => Cap::class,
            'siteKey' => $captcha['siteKey'],
            'client' => [
                'class' => YiiClient::class,
            ],
        ];

        Yii::createObject($config);
    }

    #[Depends('testConfigurationFileExists')]
    public function testEmptyClientConfiguration()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessageMatches('/client/');

        $captcha = $this->getCaptchaConfig();

        $config = [
            'class' => Cap::class,
            'siteKey' => $captcha['siteKey'],
            'secretKey' => $captcha['secretKey'],
        ];

        Yii::createObject($config);
    }

    #[Depends('testCorrectYiiClientConfiguration')]
    public function testClientWithAdapter()
    {
        $component = Yii::createObject($this->getYiiClientConfig());
        $this->assertInstanceOf(ClientInterface::class, $component->client);
    }

    #[Depends('testConfigurationFileExists')]
    public function testNotPsrClientConfiguration()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessageMatches('/Unknown/');

        $captcha = $this->getCaptchaConfig();

        $config = [
            'class' => Cap::class,
            'siteKey' => $captcha['siteKey'],
            'secretKey' => $captcha['secretKey'],
            'client' => function () {
                return \Symfony\Component\HttpClient\HttpClient::create();
            },
        ];

        Yii::createObject($config);
    }

    private function getConfigFileName(): string
    {
        return dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'captcha.php';
    }

    private function getCaptchaConfig(): array
    {
        return require $this->getConfigFileName();
    }

    private function getYiiClientConfig(): array
    {
        $captcha = $this->getCaptchaConfig();

        return [
            'class' => Cap::class,
            'siteKey' => $captcha['siteKey'],
            'secretKey' => $captcha['secretKey'],
            'client' => [
                'class' => YiiClient::class,
            ],
        ];
    }

    private function getGuzzleClientConfig(): array
    {
        $captcha = $this->getCaptchaConfig();

        return [
            'class' => Cap::class,
            'siteKey' => $captcha['siteKey'],
            'secretKey' => $captcha['secretKey'],
            'client' => [
                'class' => GuzzleClient::class,
            ],
        ];
    }

    private function getSymfonyClientConfig(): array
    {
        $captcha = $this->getCaptchaConfig();

        return [
            'class' => Cap::class,
            'siteKey' => $captcha['siteKey'],
            'secretKey' => $captcha['secretKey'],
            'client' => function () {
                return new SymfonyClient();
            },
        ];
    }
}
