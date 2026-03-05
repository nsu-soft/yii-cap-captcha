<?php

namespace nsusoft\captcha\integrations\cap\api;

use yii\base\Component;

class AbstractApi extends Component
{
    /**
     * @var string URL of Cap Captcha server.
     */
    public string $server = 'http://localhost';

    /**
     * @var int|null Port of Cap Captcha server.
     */
    public ?int $port = 3000;

    /**
     * @var string Site key.
     */
    public string $siteKey = '';

    /**
     * @var string Secret key.
     */
    public string $secretKey = '';

    /**
     * @var string API key.
     */
    public string $apiKey = '';
}