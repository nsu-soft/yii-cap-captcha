<?php

use GuzzleHttp\Client;
use nsusoft\captcha\Cap;

$captcha = require __DIR__ . '/captcha.php';

return [
    'class' => Cap::class,
    'siteKey' => $captcha['siteKey'],
    'secretKey' => $captcha['secretKey'],
    'client' => [
        'class' => Client::class,
    ],
];