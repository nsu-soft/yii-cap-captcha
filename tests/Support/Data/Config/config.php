<?php

use NsuSoft\Captcha\Cap;

$captcha = require __DIR__ . '/captcha.php';

return [
    'class' => Cap::class,
    'endpoint' => $captcha['endpoint'],
    'siteKey' => $captcha['siteKey'],
    'secretKey' => $captcha['secretKey'],
    'apiKey' => $captcha['apiKey'],
];