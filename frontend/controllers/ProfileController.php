<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\components\BaseController;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use common\models\Article;
use common\models\Course;
use common\models\CourseElement;
use common\models\Category;
use common\models\Transaction;
use common\models\ArticleReview;
use common\models\ArticleBookmark;
use common\models\User;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\helpers\Url;

/**
 * Profile controller
 */
class ProfileController extends BaseController
{
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
                        'actions' => ['articles', 'courses'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['stats'],
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
     * see articles in profile
     * @param integer $id
     * @return string
     */
    public function actionArticles($id) {
        $searchModel = new Article();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['user_id' => $id]);
        $dataProvider->query->andWhere(['is_public' => 1]);

        if ($searchModel->category && !$searchModel->category_name) {
            $searchModel->category_name = Category::getName($searchModel->category);
        }
        $transactionModel = new Transaction();
        $reviewModel = new ArticleReview();
        $bookmarkModel = new ArticleBookmark();

        $this->layout = 'blank';
        return $this->renderAjax('_articles', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
            'transactionModel' => $transactionModel,
            'reviewModel' => $reviewModel,
            'articleBookmarkModel' => $bookmarkModel,
        ]);
    }

    /**
     * see courses in profile
     * @param integer $id
     * @return string
     */
    public function actionCourses($id)
    {
        $searchModel = new Course();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['user_id' => $id]);
        $dataProvider->query->andWhere(['is_public' => 1]);

        if ($searchModel->category && !$searchModel->category_name) {
            $searchModel->category_name = Category::getName($searchModel->category);
        }
        $transactionModel = new Transaction();
        $reviewModel = new ArticleReview();
        $bookmarkModel = new ArticleBookmark();

        $this->layout = 'blank';
        return $this->renderAjax('_courses', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
            'transactionModel' => $transactionModel,
            'reviewModel' => $reviewModel,
            'articleBookmarkModel' => $bookmarkModel,
        ]);
    }

    public function actionStats($id)
    {
        // example dummy data for now
        $profit = [120, 200, 90, 400, 300, 150, 210];
        $labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        $this->layout = 'blank';
        return $this->renderAjax('_stats', [
            'labels' => $labels,
            'profit' => $profit,
        ]);
    }
}