<?php

use nsusoft\captcha\Cap;

$captcha = require __DIR__ . '/captcha.php';

return [
    'class' => Cap::class,
    'server' => $captcha['server'],
    'port' => $captcha['port'],
    'siteKey' => $captcha['siteKey'],
    'secretKey' => $captcha['secretKey'],
    'apiKey' => $captcha['apiKey'],
];