<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\models\Article;
use common\models\Category;
use common\models\Transaction;

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
        $searchModel = new Article();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(condition: ['is_public' => 1]);

        if ($searchModel->category && !$searchModel->category_name) {
            $searchModel->category_name = Category::getName($searchModel->category);
        }

        $transactionModel = new Transaction();

        return $this->render('index', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
            'transactionModel' => $transactionModel,
        ]);
    }
    /**
     * see article stats
     * @param integer $id
     * @return string
     */
    public function actionAjaxStats($public_id) {
        $searchModel = Article::findOne(condition: ['public_id' => $public_id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($searchModel->category && !$searchModel->category_name) {
            $searchModel->category_name = Category::getName($searchModel->category);
        }

        $this->layout = 'blank';
        return $this->renderAjax('ajax-stats', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * edit article content
     * @param integer $id
     * @return string
     */
    public function actionEdit($public_id) {
        $searchModel = Article::findOne(condition: ['public_id' => $public_id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($searchModel->category && !$searchModel->category_name) {
            $searchModel->category_name = Category::getName($searchModel->category);
        }

        return $this->render('edit', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * update an article
     * @param integer $id
     * @param string $page
     * @return
     */
    public function actionUpdate($id, $page)
    {
        $model = Article::findOne(['id' => $id]);
        //$model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Article changes saved succesfully.'));
            if($page == "user") {
                $this->redirect(['user/articles']);
            } else {
                $this->redirect(['article/edit', 'public_id' => $model->public_id]);
            }
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Failed to save article changes.'));
        }
        if($page == "user") {
            $this->redirect(['user/articles']);
        } else {
            $this->redirect(['article/edit', 'public_id' => $model->public_id]);
        }
    }

    /**
     * Create a new article
     * @return string
     */
    public function actionCreate(): string
    {
        $model = new Article();
        $model->user_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model = Article::find()->where('id = :id', [':id' => $model->id])->one();
            Yii::$app->session->setFlash('success', 'The article has been created.');
            $this->redirect(['article/edit', 'public_id' => $model->public_id]);
        }

        return $this->render('create' ,[
            'model' => $model,
        ]);
    }
}