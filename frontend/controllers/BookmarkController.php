<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\components\BaseController;

use yii\web\NotFoundHttpException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\models\User;
use common\models\Article;
use common\models\Course;
use common\models\Category;
use common\models\Transaction;
use common\models\ArticleReview;
use common\models\ArticleBookmark;
use common\models\CourseReview;
use common\models\CourseBookmark;

/**
 * Bookmark controller
 */
class BookmarkController extends BaseController
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
                        'actions' => ['index', 'create-article', 'delete-article', 'create-course', 'delete-course'],
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
     * Displays bookmarks page.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Article();
        $articleDataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $articleDataProvider->query->andWhere(['is_public' => 1]);

        // Get bookmarked articles by current user
        $articleBookmarkModel = new ArticleBookmark();
        $articleBookmarks = $articleBookmarkModel->findByUserId(Yii::$app->user->id);

        // Extract article IDs from bookmarks
        $bookmarkedArticleIds = array_map(function ($bookmark) {
            return $bookmark->article_id;
        }, $articleBookmarks);

        // Filter articles to those bookmarked
        $articleDataProvider->query->andWhere(['id' => $bookmarkedArticleIds]);

        //---------------------------------------------

        $searchModel = new Course();
        $courseDataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $courseDataProvider->query->andWhere(['is_public' => 1]);

        // Get bookmarked courses by current user
        $courseBookmarkModel = new CourseBookmark();
        $courseBookmarks = $courseBookmarkModel->findByUserId(Yii::$app->user->id);

        // Extract course IDs from bookmarks
        $bookmarkedCourseIds = array_map(function ($bookmark) {
            return $bookmark->course_id;
        }, $courseBookmarks);

        // Filter articles to those bookmarked
        $courseDataProvider->query->andWhere(['id' => $bookmarkedCourseIds]);

        //------------------------------------------------

        // Optional: update category name
        if ($searchModel->category && !$searchModel->category_name) {
            $searchModel->category_name = Category::getName($searchModel->category);
        }

        $transactionModel = new Transaction();
        $articleReviewModel = new ArticleReview();
        $courseReviewModel = new CourseReview();

        return $this->render('bookmarks', [
            'model' => $searchModel,
            'articleDataProvider' => $articleDataProvider,
            'courseDataProvider' => $courseDataProvider,
            'transactionModel' => $transactionModel,
            'articleReviewModel' => $articleReviewModel,
            'articleBookmarkModel' => $articleBookmarkModel,
            'courseReviewModel' => $courseReviewModel,
            'courseBookmarkModel' => $courseBookmarkModel,
        ]);
    }

    /**
     * Create a new article bookmark
     */
    public function actionCreateArticle($id, $page)
    {   
        $model = new ArticleBookmark();
        $model->user_id = Yii::$app->user->identity->id;
        $model->article_id = $id;
        
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Bookmark has been saved!');
        } else {
            Yii::$app->session->setFlash('error', 'Bookmark save failed: ' . json_encode($model->getErrors()));
        }

        return $this->redirect([$page]);
    }
    
    /**
     * Create a new course bookmark
     */
    public function actionCreateCourse($id, $page)
    {   
        $model = new CourseBookmark();
        $model->user_id = Yii::$app->user->identity->id;
        $model->course_id = $id;
        
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Bookmark has been saved!');
        } else {
            Yii::$app->session->setFlash('error', 'Bookmark save failed: ' . json_encode($model->getErrors()));
        }

        return $this->redirect([$page]);
    }

    /**
     * delete a bookmark
     */
    public function actionDeleteArticle($id, $page)
    {
        $articleBookmark = new ArticleBookmark;
        $model = $articleBookmark->findModel($id);
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'The bookmark has been deleted.');
        }

        return $this->redirect([$page]);
    }

    /**
     * delete a bookmark
     */
    public function actionDeleteCourse($id, $page)
    {
        $courseBookmark = new CourseBookmark;
        $model = $courseBookmark->findModel($id);
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'The bookmark has been deleted.');
        }

        return $this->redirect([$page]);
    }

    /**
     * Finds the Bookmark based on its id value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id - the id of the model
     * @return array|ArticleBookmark|ActiveRecord
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $id): array|ArticleBookmark|ActiveRecord
    {
        if (($model = ArticleBookmark::find()->where('id = :id', [':id' => $id])->andWhere(['user_id' => Yii::$app->user->id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested bookmark does not exist.'));
    }
}