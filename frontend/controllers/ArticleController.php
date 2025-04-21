<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\Article;
use common\models\Category;

/**
 * Article controller
 */
class ArticleController extends Controller
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
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * edit article info
     * @param integer $id
     * @return string
     */
    public function actionAjaxEdit($public_id) {
        $searchModel = Article::findOne(condition: ['public_id' => $public_id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->layout = 'blank';
        return $this->renderAjax('ajax-edit', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * update an article
     * @param integer $id
     * @return
     */
    public function actionUpdate($public_id, $page)
    {
        $model = Article::findOne(['public_id' => $public_id]);
        //$model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Article changes saved succesfully.'));
            if($page == "user") {
                $this->redirect(['user/articles']);
            } else {
                $this->redirect(['article/index']);
            }
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Failed to save article changes.'));
        }
        if($page == "user") {
            $this->redirect(['user/articles']);
        } else {
            $this->redirect(['article/index']);
        }
    }
}