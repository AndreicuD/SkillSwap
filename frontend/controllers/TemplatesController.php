<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\components\BaseController;

use yii\helpers\ArrayHelper;

/**
 * Templates controller
 */
class TemplatesController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Render Article Template
     * @return mixed
     */
    public function actionArticle(): string
    {   
        return $this->render('article');
    }

    /**
     * Render Search Template
     * @return mixed
     */
    public function actionSearch(): string
    {   
        return $this->render('search');
    }

}