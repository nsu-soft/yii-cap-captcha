<?php

use NsuSoft\Captcha\Cap;

$captcha = require __DIR__ . '/captcha.php';

return [
    'class' => Cap::class,
    'baseUri' => $captcha['baseUri'],
    'siteKey' => $captcha['siteKey'],
    'secretKey' => $captcha['secretKey'],
    'apiKey' => $captcha['apiKey'],
];