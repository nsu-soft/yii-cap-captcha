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

    public static function getConfig(): array
    {
        return require __DIR__ . '/config.php';
    }
}