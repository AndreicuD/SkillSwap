<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\db\ActiveRecord;
use common\models\Article;
use common\models\Category;
use common\models\Transaction;
use common\models\Review;
use common\models\Bookmark;

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
     * Displays index.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Article();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['is_public' => 1]);

        if ($searchModel->category && !$searchModel->category_name) {
            $searchModel->category_name = Category::getName($searchModel->category);
        }

        $transactionModel = new Transaction();
        $reviewModel = new Review();
        $bookmarkModel = new Bookmark();

        return $this->render('index', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
            'transactionModel' => $transactionModel,
            'reviewModel' => $reviewModel,
            'bookmarkModel' => $bookmarkModel,
        ]);
    }
    /**
     * see article stats
     * @param integer $id
     * @return string
     */
    public function actionAjaxStats($public_id) {
        $searchModel = Article::findOne(['public_id' => $public_id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($searchModel->category && !$searchModel->category_name) {
            $searchModel->category_name = Category::getName($searchModel->category);
        }

        $reviewModel = new Review();
        $reviewModel->value = Review::calculateRating($searchModel->id);

        $this->layout = 'blank';
        return $this->renderAjax('ajax-stats', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
            'reviewModel' => $reviewModel,
        ]);
    }

    /**
     * see article information
     * @param integer $id
     * @return string
     */
    public function actionAjaxInfo($public_id) {
        $searchModel = Article::findOne(['public_id' => $public_id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($searchModel->category && !$searchModel->category_name) {
            $searchModel->category_name = Category::getName($searchModel->category);
        }

        $this->layout = 'blank';
        return $this->renderAjax('ajax-info', [
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
        $searchModel = Article::findOne(['public_id' => $public_id]);
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
     * read an article
     * @param integer $public_id
     * @return string
     */
    public function actionRead($public_id) {
        $searchModel = Article::findOne(['public_id' => $public_id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($searchModel->category && !$searchModel->category_name) {
            $searchModel->category_name = Category::getName($searchModel->category);
        }

        $reviewModel = new Review;
        $userReviewModel = Review::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->andWhere(['article_id' => $searchModel->id])
            ->one();
        $reviewDataProvider = $reviewModel->findByArticleId($searchModel->id);

        return $this->render('read', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
            'userReviewModel' => $userReviewModel ? $userReviewModel : $reviewModel,
            'reviewModel' => $reviewModel,
            'reviewDataProvider' => $reviewDataProvider,
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

    /**
     * delete an article
     * @return
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->delete()) {
            Bookmark::deleteAll(['article_id' => $model->id]);
            Yii::$app->session->setFlash('success', 'The article has been deleted.');
        }

        $this->redirect(['user/articles']);
    }
    /**
     * Finds the Article based on its id value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id - the id of the model
     * @return array|Article|ActiveRecord
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $id): array|Article|ActiveRecord
    {
        if (($model = Article::find()->where('id = :id', [':id' => $id])->andWhere(['user_id' => Yii::$app->user->id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested article does not exist.'));
    }
}