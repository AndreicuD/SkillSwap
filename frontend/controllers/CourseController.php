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
use common\models\User;
use common\models\Category;
use common\models\Transaction;
use common\models\CourseReview;
use common\models\CourseBookmark;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\helpers\Url;
use common\models\CourseElement;
use common\models\CourseProgress;
use kartik\mpdf\Pdf;
use Mpdf\Mpdf;

/**
 * Course controller
 */
class CourseController extends BaseController
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
                        'actions' => ['update', 'edit', 'create', 'delete', 'ajax-delete', 'file-upload', 'file-delete', 'update-sort-order', 'pdf', 'unlock-next'],
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
    public function beforeAction($action)
    {
        if ($action->id === 'pdf') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
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
    public function actionUpdate($id, $page='other')
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

    /**
     * Generate a certificate pdf
     * $id is the courses public id
     * @return string
     */
    public function actionPdf() {
        //Yii::$app->request->validateCsrfToken(); // optional
        $id = Yii::$app->request->post('id');
        $course = Course::findOne(['public_id' => $id]);
        
        if (!$course) {
            throw new NotFoundHttpException('Course not found.');
        }

        $progress = CourseProgress::find()
            ->alias('cp')
            ->joinWith('element e') // assumes getElement() relation is defined
            ->where(['cp.course_id' => $course->id, 'cp.user_id' => Yii::$app->user->id])
            ->one();
        if($progress) {
            if($progress->completed_at) {
                $date = $progress->completed_at;
            }
        }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
        ]);

        $html = $this->renderPartial('//document/pdf', [
            'title' => $course->title,
            'user' => User::getName(Yii::$app->user->id),
            'date' => $date,
        ]);

        $mpdf->WriteHTML($html);
        return $mpdf->Output('certificate.pdf', \Mpdf\Output\Destination::INLINE);
    }

    public function actionUpdateSortOrder($course_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $order = Yii::$app->request->post('order', []);

            foreach ($order as $item) {
                $elementId = (int)$item['id'] ?? null;
                $sortIndex = (int)$item['sort_index'] ?? null;
                CourseElement::updateAll(['sort_index'=> $sortIndex], ['id' => $elementId]);
            }

            return ['status' => 'success'];

        } catch (\Throwable $e) {
            //Yii::error("Sort update error: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'An error occurred while updating sort order',
                'debug' => YII_DEBUG ? $e->getMessage() : null,
            ];
        }
    }


    public function actionUnlockNext()
    {
        $userId = Yii::$app->user->id;
        $courseId = Yii::$app->request->post('course_id');
        $currentElementId = Yii::$app->request->post('current_element_id');

        $currentElement = CourseElement::findOne(['element_id' => $currentElementId]);

        $nextElement = CourseElement::find()
            ->where(['course_id' => $courseId])
            ->andWhere(['>', 'sort_index', $currentElement->sort_index])
            ->orderBy(['sort_index' => SORT_ASC])
            ->one();

        if ($nextElement) {
            if ($existent = CourseProgress::find()
            ->where([
                'user_id' => $userId,
                'course_id' => $courseId,
            ])->one()) {
                $existent->element_id = $nextElement->id;
                $existent->update(false);
            } else {
                $progress = new CourseProgress();
                $progress->user_id = $userId;
                $progress->course_id = $courseId;
                $progress->element_id = $nextElement->id;
                $progress->save(false);
            }
        }

        $course = Course::findOne(['id' => $courseId]);

        return $this->redirect(['course/read', 'public_id' => $course->public_id]);
    }

}