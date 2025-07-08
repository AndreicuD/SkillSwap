<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\components\BaseController;

use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\models\User;
use common\models\Article;
use common\models\Course;
use common\models\Transaction;

/**
 * Transaction controller
 */
class TransactionController extends BaseController
{

    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

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
     * Create a new article transaction
     * @return yii\web\Response
     */
    public function actionCreate()
    {   
        $model = new Transaction();
        $model->user_id = Yii::$app->user->identity->id;
        
        if ($model->load(Yii::$app->request->post())) {
            // Fetch the current user
            $item = Article::findOne(['id' => $model->article_id]);
            if(!$item) {
                $item = Course::findOne(['id' => $model->course_id]);
            }
            $owner = User::findOne(['id' => $item->user_id]);

            $user = User::findOne(Yii::$app->user->id);
            
            // Check if the user has enough points
            if ($user->points < $model->value) {
                Yii::$app->session->setFlash('error', 'You do not have enough points to complete this transaction.');
                return $this->redirect(['site/index']);
            }
            
            $newPoints = $user->points - $model->value;

            $model->value = 0.2 * $model->value;
            $owner_newPoints = $owner->points + $model->value;

            // Save the transaction
            if (!$model->save()) {
                Yii::$app->session->setFlash('error', 'Transaction failed: ' . json_encode($model->getErrors()));
                return $this->redirect(['site/index']);
            }

            // Only save the user if there were changes to the points
            if (!$owner->updateUserPoints($item->user_id, $owner_newPoints) || !$user->updateUserPoints(Yii::$app->user->id, $newPoints)) {
                Yii::$app->session->setFlash('error', 'User update failed: ' . json_encode($user->getErrors()));
                return $this->redirect(['site/index']);
            }

            Yii::$app->session->setFlash('success', 'Transaction completed!');
            return $this->redirect(['site/index']);
        }
    }

}