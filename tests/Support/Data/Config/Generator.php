<?php

namespace Tests\Support\Data\Config;

class Generator
{
    public static function getCaptchaCredentialsFile(): string
    {
        return __DIR__ . '/captcha.php';
    }

    public static function getCaptchaCredentials(): array
    {
        return require self::getCaptchaCredentialsFile();
    }

    public static function getYiiClientConfig(): array
    {
        return require __DIR__ . '/yii.php';
    }

    public static function getGuzzleClientConfig(): array
    {
        return require __DIR__ . '/guzzle.php';
    }

    public static function getSymfonyClientConfig(): array
    {
        return require __DIR__ . '/symfony.php';
    }
}