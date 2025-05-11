<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\models\User;
use common\models\Article;
use common\models\Bookmark;

/**
 * Bookmark controller
 */
class BookmarkController extends Controller
{
    public $enableCsrfValidation = false;
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
     * Create a new article bookmark
     * @return void
     */
    public function actionCreate($id, $page)
    {   
        $model = new Bookmark();
        $model->user_id = Yii::$app->user->identity->id;
        $model->article_id = $id;
        
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Bookmark has been saved!');
        } else {
            Yii::$app->session->setFlash('error', 'Bookmark save failed: ' . json_encode($model->getErrors()));
        }

        $this->redirect([$page]);
    }

    /**
     * delete a bookmark
     * @return
     */
    public function actionDelete($id, $page)
    {
        $model = $this->findModel($id);
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'The bookmark has been deleted.');
        }

        $this->redirect([$page]);
    }
    /**
     * Finds the Bookmark based on its id value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id - the id of the model
     * @return array|Bookmark|ActiveRecord
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $id): array|Bookmark|ActiveRecord
    {
        if (($model = Bookmark::find()->where('id = :id', [':id' => $id])->andWhere(['user_id' => Yii::$app->user->id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested bookmark does not exist.'));
    }
}