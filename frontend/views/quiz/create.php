<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

$this->title = Yii::t('app', 'Add Quiz To Course');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1 style="text-align: center;" class="page_title"><?= Html::encode($this->title) ?></h1>

    <div class="padd-15">
        <?php $form = ActiveForm::begin([
            'id' => 'quiz-form',
            'type' => ActiveForm::TYPE_FLOATING,
            'action' => ['quiz/create', 'course_id' => $course->id], // Specify the route to the create action
            'method' => 'post',
        ]); ?>
    
        <?= $form->errorSummary($model);?>
        <?= $form->field($model, 'title')->label(Yii::t('app', 'Title')) ?>
    
        <div class="w-100 text-center">
            <?= Html::button(Yii::t('app', 'Create Quiz'),['class' => ['btn btn-primary rotate_on_hover scale_on_hover mb-3'], 'type' => 'submit']) ?>
        </div>
    
        <?php ActiveForm::end(); ?>
    </div>
</div>