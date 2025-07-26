<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;
use common\models\Article;
use common\models\Quiz;

$this->title = $model->title;
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1 style="text-align: center;" class="page_title"><?= Html::encode($this->title) ?> - <?= Yii::t('app', 'Quiz') ?></h1>
     
    <div class="padd-20">
        <div class="padd-15">
            <?php $form = ActiveForm::begin([
                'id' => 'quiz-form',
                'type' => ActiveForm::TYPE_FLOATING,
                'action' => ['quiz/update', 'public_id' => $model->public_id, 'course_id' => $course_id], // Specify the route to the create action
                'method' => 'post',
            ]); ?>
        
            <?= $form->errorSummary($model);?>
            <?= $form->field($model, 'title')->label(Yii::t('app', 'Title')) ?>
        
            <div class="group_together">
                <div style="width: 100%; text-align: center;">
                    <?= Html::a(Yii::t('app', 'Go Back'),['/course/edit', 'public_id' => $model->course->public_id],['class' => ['btn btn-outline-danger rotate_on_hover mb-3']]) ?>
                </div>
                <div style="width: 100%; text-align: center;">
                    <?= Html::button(Yii::t('app', 'Save New Title'),['class' => ['btn btn-primary rotate_on_hover scale_on_hover mb-3'], 'type' => 'submit']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        
        <hr>
        <div class="padd-15 text-center">
            <p class="gray text-sm">Any question you save that has no answers <span style="color: red;">will be skipped</span> for the user!</p>
            <?= Html::a(Yii::t('app', 'Add New Question'),['/quiz/create-question', 'quiz_id' => $model->public_id],['class' => ['btn btn-outline-secondary scale_on_hover mb-3']]) ?>
        </div>


        <div class="flex-row-even">
            <?php foreach ($model->questions as $question): ?>
                <div class="card quiz-question mb-4 p-2">
                    <?php $qForm = ActiveForm::begin([
                        'action' => ['quiz/update-question', 'id' => $question->id],
                        'method' => 'post',
                        'options' => ['class' => 'd-flex flex-row-even align-items-center']
                    ]); ?>
                    <div clas='w-20'>
                        <?= $qForm->field($question, 'text')->textInput(['class' => 'w-100'])->label(false) ?>
                    </div>
                    <div class="w-20">
                        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'mb-3 btn btn-primary w-100 scale_on_hover rotate_on_hover']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>

                    <hr style="margin: 0 0 1em; padding: 0;">

                    <div class="text-center mb-3">
                        <?= Html::a(Yii::t('app', 'Add Choice'), ['quiz/create-choice', 'question_id' => $question->id], ['class' => 'btn btn-outline-secondary w-100 rotate_on_hover']) ?>
                    </div>
                    
                    <div class="flex-row-even">
                        <?php foreach ($question->choices as $choice): ?>
                            <div class="card quiz-question p-2">
                                <?php $cForm = ActiveForm::begin([
                                    'action' => ['quiz/update-choice', 'id' => $choice->id],
                                    'type' => ActiveForm::TYPE_FLOATING,
                                    'method' => 'post',
                                ]); ?>

                                <?= $cForm->field($choice, 'text')->textInput()->label(Yii::t('app', 'Choice Text')) ?>

                                <?= $cForm->field($choice, 'correct')->checkbox(['label' => Yii::t('app', 'Correct Answer')]) ?>

                                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-sm btn-primary w-100 mb-1']) ?>

                                <?php ActiveForm::end(); ?>
                                <?= Html::a(Yii::t('app', 'Delete Choice'), ['quiz/delete-choice', 'id' => $choice->id], ['class' => 'btn btn-sm btn-outline-danger w-100']) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-center mb-3">
                        <?= Html::a(Yii::t('app', 'Delete Question'), ['quiz/delete-question', 'id' => $question->id], ['class' => 'btn btn-danger w-100 rotate_on_hover']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>