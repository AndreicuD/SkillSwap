<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\User;
use common\models\Article;
use common\models\Review;

/**
 * Review controller
 */
class ReviewController extends Controller
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
     * Create a new article review
     * @return \yii\web\Response|null
     */
    
    public function actionCreate($public_id)
    {   
        $model = new Review();
        $model->user_id = Yii::$app->user->identity->id;
        
        if ($model->load(Yii::$app->request->post())) {
            // Fetch the current user
            $article = Article::findOne(['id' => $model->article_id]);

            // Save the transaction
            if (!$model->save()) {
                Yii::$app->session->setFlash('error', 'Review failed: ' . json_encode($model->getErrors()));
                return $this->redirect('/article/read?public_id=' . $public_id);
            }

            Yii::$app->session->setFlash('success', 'Review has been saved!');
            return $this->redirect('/article/read?public_id=' . $public_id);
        }
    }

    /**
     * update a review
     * @param integer $public_id
     * @return
     */
    public function actionUpdate($public_id)
    {   
        $article = Article::findOne(['public_id' => $public_id]);
        $model = Review::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->andWhere(['article_id' => $article->id])
            ->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Review changes saved succesfully.'));
            $this->redirect(['/article/read?public_id=' . $public_id]);
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Failed to save review changes.'));
        }
        $this->redirect(['/article/read?public_id=' . $public_id]);
    }
}