<?php

use nsusoft\captcha\Cap;
use Symfony\Component\HttpClient\Psr18Client;

$captcha = require __DIR__ . '/captcha.php';

return [
    'class' => Cap::class,
    'siteKey' => $captcha['siteKey'],
    'secretKey' => $captcha['secretKey'],
    'client' => function () {
        return new Psr18Client();
    },
];