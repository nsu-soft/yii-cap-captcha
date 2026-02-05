<?php

namespace Tests\Unit;

use Codeception\Attribute\Depends;
use nsusoft\captcha\Cap;
use Tests\Support\UnitTester;
use Yii;
use yii\base\InvalidConfigException;

class CapTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    protected function _before()
    {
    }

    public function testConfigurationFileExists()
    {
        $captchaFile = $this->getConfigurationFileName();
        $this->assertFileExists($captchaFile, "Captcha configuration file doesn't exist.");
    }

    #[Depends('testConfigurationFileExists')]
    public function testCorrectYiiHttpClientConfiguration()
    {
        $this->expectNotToPerformAssertions();

        $captcha = $this->getCaptchaConfiguration();

        $config = [
            'class' => Cap::class,
            'siteKey' => $captcha['siteKey'],
            'secretKey' => $captcha['secretKey'],
            'client' => [
                'class' => \yii\httpclient\Client::class,
            ],
        ];

        Yii::createObject($config);
    }

    #[Depends('testConfigurationFileExists')]
    public function testCorrectGuzzleClientConfiguration()
    {
        $this->expectNotToPerformAssertions();

        $captcha = $this->getCaptchaConfiguration();

        $config = [
            'class' => Cap::class,
            'siteKey' => $captcha['siteKey'],
            'secretKey' => $captcha['secretKey'],
            'client' => [

                'class' => \GuzzleHttp\Client::class,
            ],
        ];

        Yii::createObject($config);
    }

    #[Depends('testConfigurationFileExists')]
    public function testIncorrectSiteKeyConfiguration()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessageMatches('/site key/');

        $captcha = $this->getCaptchaConfiguration();

        $config = [
            'class' => Cap::class,
            'secretKey' => $captcha['secretKey'],
            'client' => [
                'class' => \yii\httpclient\Client::class,
            ],
        ];

        Yii::createObject($config);
    }

    #[Depends('testConfigurationFileExists')]
    public function testIncorrectSecretKeyConfiguration()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessageMatches('/secret key/');

        $captcha = $this->getCaptchaConfiguration();

        $config = [
            'class' => Cap::class,
            'siteKey' => $captcha['siteKey'],
            'client' => [
                'class' => \yii\httpclient\Client::class,
            ],
        ];

        Yii::createObject($config);
    }

    #[Depends('testConfigurationFileExists')]
    public function testIncorrectClientConfiguration()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessageMatches('/client/');

        $captcha = $this->getCaptchaConfiguration();

        $config = [
            'class' => Cap::class,
            'siteKey' => $captcha['siteKey'],
            'secretKey' => $captcha['secretKey'],
        ];

        Yii::createObject($config);
    }

    private function getConfigurationFileName(): string
    {
        return dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'captcha.php';
    }

    private function getCaptchaConfiguration(): array
    {
        return require $this->getConfigurationFileName();
    }
}
