<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use common\models\Course;
use common\models\Category;
use common\models\Transaction;
use common\models\CourseReview;
use common\models\CourseBookmark;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\helpers\Url;

/**
 * Course controller
 */
class CourseController extends Controller
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
                        'actions' => ['update', 'edit', 'create', 'delete', 'ajax-delete', 'file-upload', 'file-delete'],
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
        $searchModel = new Course();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['is_public' => 1]);

        if ($searchModel->category && !$searchModel->category_name) {
            $searchModel->category_name = Category::getName($searchModel->category);
        }

        $transactionModel = new Transaction();
        $reviewModel = new CourseReview();
        $bookmarkModel = new CourseBookmark();

        return $this->render('index', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
            'transactionModel' => $transactionModel,
            'reviewModel' => $reviewModel,
            'courseBookmarkModel' => $bookmarkModel,
        ]);
    }
    /**
     * see course stats
     * @param integer $id
     * @return string
     */
    public function actionAjaxStats($public_id) {
        $searchModel = Course::findOne(['public_id' => $public_id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($searchModel->category && !$searchModel->category_name) {
            $searchModel->category_name = Category::getName($searchModel->category);
        }

        $reviewModel = new CourseReview();
        $reviewModel->value = CourseReview::calculateRating($searchModel->id);

        $this->layout = 'blank';
        return $this->renderAjax('ajax-stats', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
            'reviewModel' => $reviewModel,
        ]);
    }

    /**
     * see course information
     * @param integer $id
     * @return string
     */
    public function actionAjaxInfo($public_id) {
        $searchModel = Course::findOne(['public_id' => $public_id]);
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
        $searchModel = Course::findOne(['public_id' => $public_id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->layout = 'blank';
        return $this->renderAjax('ajax-delete', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * edit course content
     * @param integer $id
     * @return string
     */
    public function actionEdit($public_id) {
        $searchModel = Course::findOne(['public_id' => $public_id]);
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
     * read a course
     * @param integer $public_id
     * @return string
     */
    public function actionRead($public_id) {
        $searchModel = Course::findOne(['public_id' => $public_id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($searchModel->category && !$searchModel->category_name) {
            $searchModel->category_name = Category::getName($searchModel->category);
        }

        $reviewModel = new CourseReview;
        $userReviewModel = CourseReview::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->andWhere(['course_id' => $searchModel->id])
            ->one();
        $reviewDataProvider = $reviewModel->findByCourseId($searchModel->id);

        return $this->render('read', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
            'userReviewModel' => $userReviewModel ? $userReviewModel : $reviewModel,
            'reviewModel' => $reviewModel,
            'reviewDataProvider' => $reviewDataProvider,
        ]);
    }


    /**
     * update a course
     * @param integer $id
     * @param string $page
     * @return
     */
    public function actionUpdate($id, $page)
    {
        $model = Course::findOne(['id' => $id]);
        //$model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $file = UploadedFile::getInstanceByName('Course[cover]');
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

            Yii::$app->session->setFlash('success', Yii::t('app', 'Course changes saved succesfully.'));
            if($page == "user") {
                $this->redirect(['user/courses']);
            } else {
                $this->redirect(['course/edit', 'public_id' => $model->public_id]);
            }
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Failed to save course changes.'));
        }
        if($page == "user") {
            $this->redirect(['user/courses']);
        } else {
            $this->redirect(['course/edit', 'public_id' => $model->public_id]);
        }
    }

    /**
     * Create a new course
     * @return string
     */
    public function actionCreate(): string
    {
        $model = new Course();
        $model->user_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model = Course::find()->where('id = :id', [':id' => $model->id])->one();
            Yii::$app->session->setFlash('success', 'The course has been created.');
            $this->redirect(['course/edit', 'public_id' => $model->public_id]);
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
            CourseBookmark::deleteAll(['course_id' => $model->id]);
            CourseReview::deleteAll(['course_id' => $model->id]);
            Yii::$app->session->setFlash('success', 'The course has been deleted.');
        }

        $this->redirect(['user/courses']);
    }
    /**
     * Finds the Course based on its id value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id - the id of the model
     * @return array|Course|ActiveRecord
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $id): array|Course|ActiveRecord
    {
        if (($model = Course::find()->where('id = :id', [':id' => $id])->andWhere(['user_id' => Yii::$app->user->id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested course does not exist.'));
    }

        /**
     * @param integer $id
     * @return array|false
     */
    public function actionFileUpload(int $id): array|false
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        $file = UploadedFile::getInstanceByName('Course[cover]');
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
                                'url' => Url::to(['course/file-delete', 'id' => $model->id]),
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