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
                    <?= Html::a(Yii::t('app', 'Go Back'),['/course/edit', 'public_id' => $course_id],['class' => ['btn btn-outline-danger rotate_on_hover mb-3']]) ?>
                </div>
                <div style="width: 100%; text-align: center;">
                    <?= Html::button(Yii::t('app', 'Save New Title'),['class' => ['btn btn-primary rotate_on_hover scale_on_hover mb-3'], 'type' => 'submit']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        
        <hr>
        <div class="padd-15 text-center">
            <?= Html::a(Yii::t('app', 'Add New Question'),['/quiz/create-question', 'quiz_id' => $model->public_id],['class' => ['btn btn-outline-secondary scale_on_hover mb-3']]) ?>
        </div>



        <?php foreach ($model->questions as $question): ?>
            <div class="card mb-4 p-3 border rounded shadow-sm">
                <?php $qForm = ActiveForm::begin([
                    'action' => ['question/update', 'id' => $question->id],
                    'method' => 'post',
                    'options' => ['class' => 'd-flex justify-content-between align-items-center gap-2']
                ]); ?>
                    <?= $qForm->field($question, 'text')->textInput(['style' => 'flex: 1'])->label(false) ?>
                    <?= Html::submitButton(Yii::t('app', 'Save Btn'), ['class' => 'btn btn-primary']) ?>
                <?php ActiveForm::end(); ?>

                <div class="text-end mt-2">
                    <?= Html::a(Yii::t('app', 'Add Choice'), ['choice/create', 'question_id' => $question->id], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                </div>

                <div class="row mt-3">
                    <?php foreach ($question->choices as $choice): ?>
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded">
                                <?php $cForm = ActiveForm::begin([
                                    'action' => ['choice/update', 'id' => $choice->id],
                                    'method' => 'post',
                                ]); ?>

                                <?= $cForm->field($choice, 'text')->textInput()->label(Yii::t('app', 'Choice Text')) ?>

                                <?= $cForm->field($choice, 'is_correct')->checkbox(['label' => Yii::t('app', 'correct?')]) ?>

                                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-sm btn-success']) ?>

                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</div>