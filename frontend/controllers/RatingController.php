<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\User;
use common\models\Article;
use common\models\Rating;

/**
 * Rating controller
 */
class RatingController extends Controller
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
     * Create a new article rating
     * @return void
     */
    public function actionCreate($page)
    {   
        $model = new Rating();
        $model->user_id = Yii::$app->user->identity->id;
        
        if ($model->load(Yii::$app->request->post())) {
            // Fetch the current user
            $article = Article::findOne(['id' => $model->article_id]);

            // Save the transaction
            if (!$model->save()) {
                Yii::$app->session->setFlash('error', 'Rating failed: ' . json_encode($model->getErrors()));
                return $this->redirect([$page]);
            }

            Yii::$app->session->setFlash('success', 'Rating completed!');
            return $this->redirect([$page]);
        }
    }

}