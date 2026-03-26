<?php

namespace app\controllers;

use NsuSoft\Captcha\Filters\CapFilter;
use Yii;
use yii\web\Controller;

class TestController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return [
            'captcha' => [
                'class' => CapFilter::class,
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        return 'index';
    }

    /**
     * @return string
     */
    public function actionForm(): string
    {
        if (Yii::$app->request->isPost) {
            return 'submited';
        }
        
        return $this->render('form');
    }
}