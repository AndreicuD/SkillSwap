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
 * Article controller
 */
class ArticleController extends BaseController
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
                        'actions' => ['read', 'ajax-info', 'ajax-stats', '_review', 'index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['update', 'edit', 'course-edit', 'create', 'create-in-course', 'delete', 'ajax-delete', 'file-upload', 'file-delete'],
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
        $reviewModel = new ArticleReview();
        $bookmarkModel = new ArticleBookmark();

        return $this->render('index', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
            'transactionModel' => $transactionModel,
            'reviewModel' => $reviewModel,
            'articleBookmarkModel' => $bookmarkModel,
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

        $reviewModel = new ArticleReview();
        $reviewModel->value = ArticleReview::calculateRating($searchModel->id);

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
     * see delete confirmation
     * @param integer $id
     * @return string
     */
    public function actionAjaxDelete($public_id) {
        $searchModel = Article::findOne(['public_id' => $public_id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->layout = 'blank';
        return $this->renderAjax('ajax-delete', [
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
     * edit article (from a course) content
     * @param integer $id
     * @return string
     */
    public function actionCourseEdit($public_id) {
        $searchModel = Article::findOne(['public_id' => $public_id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($searchModel->category && !$searchModel->category_name) {
            $searchModel->category_name = Category::getName($searchModel->category);
        }

        return $this->render('course-edit', [
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

        $reviewModel = new ArticleReview;
        $userReviewModel = ArticleReview::find()
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
            $file = UploadedFile::getInstanceByName('Article[cover]');
            if ($file) {
                $model->cover_extension = $file->getExtension();
                if ($model->save()) {
                    if (!file_exists($model->getFolder(true))) {
                        @mkdir($model->getFolder(true), 0777, true);
                    }
                    if (file_exists($model->getFolder(true)) && $file->saveAs($model->getFilePath())) {
                        Yii::$app->session->setFlash('success', Yii::t('app', 'The image was saved succesfully.'));
                    }
                }
            }

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
     * Create a new article
     * @return string
     */
    public function actionCreateInCourse($course_id): string
    {
        $model = new Article();
        $model->user_id = Yii::$app->user->id;

        $course = Course::findOne(['id' => $course_id]);

        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model = Article::find()->where('id = :id', [':id' => $model->id])->one();

            $element = new CourseElement([
                'course_id' => $course_id,
                'element_type' => 'article',
                'element_id' => $model->id,
            ]);
            $element->save();
            
            Yii::$app->session->setFlash('success', 'The article has been created.');
            $this->redirect(['article/course-edit', 'public_id' => $model->public_id]);
        }

        return $this->render('create-in-course' ,[
            'model' => $model,
            'course' => $course,
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
            ArticleBookmark::deleteAll(['article_id' => $model->id]);
            ArticleReview::deleteAll(['article_id' => $model->id]);
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

    /**
    * @param integer $id
    * @return array|false
    */
    public function actionFileUpload(int $id): array|false
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        $file = UploadedFile::getInstanceByName('Article[cover]');
        if ($file) {
            $model->cover_extension = $file->getExtension();
            if ($model->save()) {
                if (!file_exists($model->getFolder(true))) {
                    @mkdir($model->getFolder(true), 0777, true);
                }
                if (file_exists($model->getFolder(true)) && $file->saveAs($model->getFilePath())) {
                    return [
                        'initialPreview' => $model->getSrc(),
                        'initialPreviewConfig' => [
                            [
                                'url' => Url::to(['article/file-delete', 'id' => $model->id]),
                                'type' => 'image',
                                'fileId' => $model->id,
                            ]
                        ],
                        'append' => true
                    ];
                }
            }
        }
        return false;
    }

    /**
    * @param integer $id
    * @return bool
    */
    public function actionFileDelete(int $id): bool
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        return unlink($model->getFilePath());
    }
}