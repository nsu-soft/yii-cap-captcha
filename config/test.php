<?php

use NsuSoft\Captcha\Cap;
use yii\helpers\ArrayHelper;

$captcha = require __DIR__ . '/captcha.php';

/**
 * Application configuration shared by all test types
 */
return [
    'id' => 'library-tests',
    'basePath' => dirname(__DIR__),
    'components' => [
        'captcha' => ArrayHelper::merge($captcha, [
            'class' => Cap::class,
        ]),
    ],
];