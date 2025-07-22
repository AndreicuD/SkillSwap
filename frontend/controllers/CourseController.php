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
use common\models\Category;
use common\models\Transaction;
use common\models\CourseReview;
use common\models\CourseBookmark;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\helpers\Url;
use common\models\CourseElement;
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
                        'actions' => ['update', 'edit', 'create', 'delete', 'ajax-delete', 'file-upload', 'file-delete', 'update-sort-order'],
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

    /**
     * Summary of actionTestPdf
     * @return void
     */
    public function actionTestPdf() {
        $pdf = new Pdf([
            //'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'mode' => Pdf::MODE_UTF8,
            'format' => 'A4',
            'orientation' => 'L',
            //'marginLeft' => $this->margin_left,
            //'marginRight' => $this->margin_right,
            //'marginTop' => $this->margin_top,
            //'marginBottom' => $this->margin_bottom,
            //'marginHeader' => $this->margin_header,
            //'marginFooter' => $this->margin_footer,
            'destination' => Pdf::DEST_BROWSER,
            'content' =>  Yii::$app->controller->renderPartial('//document/pdf', ['content' => 'aici e continutul']),
            'filename' => 'nume.pdf',
            'options' => [
                'mode' => 'utf-8',
                /*'fontDir' => array_merge($fontDirs, [
                    Yii::getAlias('@frontend').DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.'fonts',
                ]),
                'fontdata' => $fontData + [ // lowercase letters only in font key
                        'montserrat' => [
                            'R' => 'Montserrat-Regular.ttf',
                        ]
                    ],
                'default_font' => 'montserrat'*/
            ],
            /*'methods' => [
                'SetTitle' => $this->name,
                'SetSubject' => $this->name,
                'SetHeader' => [],
                'SetFooter' => ['{PAGENO}/{nbpg}'],
                'SetAuthor' => '',
                'SetCreator' => '',
                'SetKeywords' => '',
            ]*/
        ]);
        $pdf->render();
    }

    public function actionUpdateSortOrder($course_id)
    {
        print_r(json_encode(Yii::$app->request->post()));
        exit(0);
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $order = Yii::$app->request->post('order', []);

            if (!is_array($order)) {
                throw new \Exception("Invalid order data");
            }

            foreach ($order as $item) {
                $elementId = $item['id'] ?? null;
                $sortIndex = $item['sort_index'] ?? null;

                if ($elementId === null || $sortIndex === null) {
                    continue;
                }

                $element = CourseElement::findOne(['id' => $elementId, 'course_id' => $course_id]);

                if (!$element) {
                    Yii::warning("CourseElement not found for ID: $elementId and course_id: $course_id");
                    continue;
                }

                $element->sort_index = (int)$sortIndex;
                if (!$element->save()) {
                    Yii::error("Failed to save CourseElement ID $elementId: " . json_encode($element->getErrors()));
                }
            }

            // ✅ Optional: normalize sort_index to 10, 20, 30,...
            $normalized = CourseElement::find()
                ->where(['course_id' => $course_id])
                ->orderBy(['sort_index' => SORT_ASC])
                ->all();

            $i = 10;
            foreach ($normalized as $item) {
                $item->sort_index = $i;
                $item->save(false); // skip validation
                $i += 10;
            }

            return ['status' => 'success'];
        } catch (\Throwable $e) {
            Yii::error("Sort update error: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'An error occurred while updating sort order',
                'debug' => YII_DEBUG ? $e->getMessage() : null,
            ];
        }
    }

}