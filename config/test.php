<?php

use nsusoft\captcha\Cap;
use yii\helpers\ArrayHelper;

$captcha = require __DIR__ . '/captcha.php';

/**
 * Application configuration shared by all test types
 */
return [
    'id' => 'library-tests',
    'basePath' => dirname(__DIR__),
    // 'aliases' => [
    //     '@bower' => '@vendor/bower-asset',
    //     '@npm'   => '@vendor/npm-asset',
    // ],
    // 'language' => 'en-US',
    'components' => [
        // 'assetManager' => [
        //     'basePath' => dirname(__DIR__) . '/web/assets',
        // ],
        'captcha' => ArrayHelper::merge($captcha, [
            'class' => Cap::class,
            'client' => [
                'class' => \yii\httpclient\Client::class,
            ],
        ]),
        // 'request' => [
        //     'cookieValidationKey' => 'test',
        //     'enableCsrfValidation' => false,
        // ],
        // 'urlManager' => [
        //     'showScriptName' => true,
        // ],
    ],
];