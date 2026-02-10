<?php

use nsusoft\captcha\Cap;
use yii\httpclient\Client;

$captcha = require __DIR__ . '/captcha.php';

return [
    'class' => Cap::class,
    'siteKey' => $captcha['siteKey'],
    'secretKey' => $captcha['secretKey'],
    'client' => [
        'class' => Client::class,
    ],
];