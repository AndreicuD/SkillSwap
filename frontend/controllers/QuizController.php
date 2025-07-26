<?php

namespace frontend\controllers;

use common\models\QuizChoice;
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
                            'create', 'edit', 'update', 'ajax-delete', 'delete', 'submit',
                            'create-question', 'update-question', 'delete-question',
                            'create-choice', 'update-choice', 'delete-choice',
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
     * submit answers to questions of a quiz
     * @return Yii\web\Response
     */
    public function actionSubmit()
    {
        $quizId = Yii::$app->request->post('quiz_id');
        $submittedAnswers = Yii::$app->request->post('answers');

        $quiz = Quiz::findOne($quizId);
        $questions = $quiz->questions;

        $score = 0;
        $results = [];

        foreach ($questions as $question) {
            $correctAnswers = $question->getChoices()->where(['correct' => 1])->all();

            $correctAnswerIds = array_map(fn($c) => $c->id, $correctAnswers);
            $userAnswerIds = $submittedAnswers[$question->id] ?? null;
            
            sort($userAnswerIds);
            sort($correctAnswerIds);
            
            
            $isCorrect = $userAnswerIds == $correctAnswerIds;
            $results[$question->id] = $isCorrect;
            if ($isCorrect) {
                $score++;
            }

            // TO DO: Save user progress to a new table
        }

        Yii::$app->session->setFlash('success', "You scored $score / " . count($questions));
        return $this->redirect(Yii::$app->request->referrer);
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
            $this->redirect(['quiz/edit', 'public_id' => $model->public_id, 'course_id' => $course->public_id]);
        }

        return $this->render('create' ,[
            'model' => $model,
            'course' => $course,
        ]);
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
     * @return Response
     */
    public function actionUpdate($public_id, $course_id) 
    {
        $model = Quiz::findOne(['public_id' => $public_id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Quiz changes saved succesfully.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Failed to save quiz changes.'));
        }

        $this->redirect(['quiz/edit', 'public_id' => $public_id, 'course_id' => $course_id]);
    }

    /**
     * delete a quiz
     * @return
     */
    public function actionDelete($id, $course_id)
    {
        $model = Quiz::findOne(['id' => $id]);
        if ($model->delete()) {
            $questionIds = QuizQuestion::find()
                ->select('id')
                ->where(['quiz_id' => $model->id])
                ->column();
            if (!empty($questionIds)) {
                QuizChoice::deleteAll(['in', 'question_id', $questionIds]);
            }

            QuizQuestion::deleteAll(['quiz_id' => $model->id]);

            CourseElement::deleteAll([
                'and',
                ['element_type' => 'quiz'],
                ['element_id' => $model->id]
            ]);
            Yii::$app->session->setFlash('success', 'The quiz has been deleted.');
        }

        $this->redirect(['course/edit', 'public_id' => $course_id]);
    }

    /**
     * Create a new quiz question
     * @return Response
     */
    public function actionCreateQuestion($quiz_id)
    {
        $quiz = Quiz::findOne(['public_id' => $quiz_id]);
        if (!$quiz) {
            throw new NotFoundHttpException("Quiz not found.");
        }

        $model = new QuizQuestion();
        $model->quiz_id = $quiz->id;
        $model->text = Yii::t('app','Type your question');

        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'The question has been created.');
        } else {
            Yii::error($model->getErrors(), __METHOD__);
            Yii::$app->session->setFlash('error', 'There was an error creating the question.');
        }

        return $this->redirect(['quiz/edit', 'public_id' => $quiz->public_id, 'course_id' => $quiz->course_id]);
    }

    /**
     * update quiz question
     * @param integer $id
     * @return string
     */
    public function actionUpdateQuestion($id) {
        $model = QuizQuestion::findOne(['id' => $id]);
        $quiz = $model->quiz;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Question changes saved succesfully.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Failed to save question changes.'));
        }

        $this->redirect(['quiz/edit', 'public_id' => $quiz->public_id, 'course_id' => $quiz->course_id]);
    }

    /**
     * delete a quiz question
     * @return
     */
    public function actionDeleteQuestion($id)
    {
        $model = QuizQuestion::findOne(['id' => $id]);
        $quiz = $model->quiz;
        if ($model->delete()) {
            QuizChoice::deleteAll(['question_id' => $id]);

            Yii::$app->session->setFlash('success', 'The quiz question has been deleted.');
        }

        return $this->redirect(['quiz/edit', 'public_id' => $quiz->public_id, 'course_id' => $quiz->course_id]);
    }

    /**
     * Create a new question choice
     * @return Response
     */
    public function actionCreateChoice($question_id)
    {
        $question = QuizQuestion::findOne(['id' => $question_id]);
        if (!$question) {
            throw new NotFoundHttpException("Question not found.");
        }

        $model = new QuizChoice();
        $model->question_id = $question->id;
        $model->text = 'Choice Text';
        $model->correct = 0;

        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'The choice has been created.');
        } else {
            Yii::error($model->getErrors(), __METHOD__);
            print_r($model->getErrors());
            exit(0);
            Yii::$app->session->setFlash('error', 'There was an error creating the choice.');
        }
        $quiz = $question->quiz;

        return $this->redirect(['quiz/edit', 'public_id' => $quiz->public_id, 'course_id' => $quiz->course_id]);
    }

    /**
     * update question choice
     * @param integer $id
     * @return string
     */
    public function actionUpdateChoice($id) {
        $model = QuizChoice::findOne(['id' => $id]);
        $quiz = $model->question->quiz;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Question changes saved succesfully.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Failed to save question changes.'));
        }

        $this->redirect(['quiz/edit', 'public_id' => $quiz->public_id, 'course_id' => $quiz->course_id]);
    }

    /**
     * delete a question choice
     * @return
     */
    public function actionDeleteChoice($id)
    {
        $model = QuizChoice::findOne(['id' => $id]);
        $quiz = $model->question->quiz;
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'The quiz choice has been deleted.');
        }

        return $this->redirect(['quiz/edit', 'public_id' => $quiz->public_id, 'course_id' => $quiz->course_id]);
    }
}