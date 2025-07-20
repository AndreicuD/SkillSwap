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
            <?= Html::a(Yii::t('app', 'Add New Question'),['/quiz_question/create'],['class' => ['btn btn-outline-secondary scale_on_hover mb-3']]) ?>
        </div>
    </div>
</div>