<?php

namespace frontend\controllers;

use common\models\QuizQuestion;
use Yii;
use yii\web\Controller;
use frontend\components\BaseController;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use common\models\Quiz;
use common\models\Course;
use common\models\CourseElement;
use common\models\User;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\helpers\Url;

/**
 * Quiz controller
 */
class QuizController extends BaseController
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
                        'actions' => [
                            'create', 'edit', 'update', 'ajax-delete', 'delete',
                            'create-question'
                        ],
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
     * see delete confirmation
     * @param integer $id
     * @return string
     */
    public function actionAjaxDelete($public_id, $course_id) {
        $searchModel = Quiz::findOne(['public_id' => $public_id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->layout = 'blank';
        return $this->renderAjax('ajax-delete', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
            'course_id' => $course_id,
        ]);
    }

    /**
     * Create a new quiz
     * @return string
     */
    public function actionCreate($course_id): string
    {
        $model = new Quiz();
        $model->course_id = $course_id;
        $model->title = 'Unnamed';

        $course = Course::findOne(['id' => $course_id]);

        if ($model->save()) {
            $model = Quiz::find()->where('id = :id', [':id' => $model->id])->one();

            $element = new CourseElement([
                'course_id' => $course_id,
                'element_type' => 'quiz',
                'element_id' => $model->id,
            ]);
            $element->save();
            
            Yii::$app->session->setFlash('success', 'The quiz has been created.');
            $this->redirect(['quiz/edit', 'public_id' => $model->public_id]);
        }

        return $this->render('create' ,[
            'model' => $model,
            'course' => $course,
        ]);
    }

    /**
     * Create a new quiz question
     * @return void
     */
    public function actionCreateQuestion($quiz_id)
    {
        $quiz = Quiz::findOne(['public_id' => $quiz_id]);
        if (!$quiz) {
            throw new NotFoundHttpException("Quiz not found.");
        }

        $model = new QuizQuestion();
        $model->quiz_id = $quiz->id;
        $model->text = Yii::t('app','What is the question?');

        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'The question has been created.');
        } else {
            Yii::error($model->getErrors(), __METHOD__);
            Yii::$app->session->setFlash('error', 'There was an error creating the question.');
        }

        return $this->redirect(['quiz/edit', 'public_id' => $quiz->public_id, 'course_id' => $quiz->course_id]);
    }


    /**
     * edit quiz content
     * @param integer $id
     * @return string
     */
    public function actionEdit($public_id, $course_id) {
        $model = Quiz::findOne(['public_id' => $public_id]);
        $dataProvider = $model->search(Yii::$app->request->queryParams);

        return $this->render('edit', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'course_id' => $course_id,
        ]);
    }

    /**
     * update quiz content
     * @param integer $id
     * @return string
     */
    public function actionUpdate($public_id, $course_id) {
        $model = Quiz::findOne(['public_id' => $public_id]);
        $dataProvider = $model->search(Yii::$app->request->queryParams);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Quiz changes saved succesfully.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Failed to save quiz changes.'));
        }

        $this->redirect(['quiz/edit', 'public_id' => $public_id, 'course_id' => $course_id]);
    }

    /**
     * delete an article
     * @return
     */
    public function actionDelete($id, $course_id)
    {
        $model = Quiz::findOne(['id' => $id]);
        if ($model->delete()) {
            //QuizQuestion::deleteAll(['article_id' => $model->id]);
            //QuizChoice::deleteAll(['article_id' => $model->id]);
            Yii::$app->session->setFlash('success', 'The quiz has been deleted.');
        }

        $this->redirect(['course/edit', 'public_id' => $course_id]);
    }
}