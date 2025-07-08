<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\widgets\ListView;
use kartik\widgets\ActiveForm;
use yii\bootstrap5\Modal;
use yii\web\View;
use common\models\Category;
use kartik\select2\Select2;

$this->title = Yii::t('app', 'Add Article To Course');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1 style="text-align: center;" class="page_title"><?= Html::encode($this->title) ?></h1>

    <div class="padd-15">
        <?php $form = ActiveForm::begin([
            'id' => 'article-form',
            'type' => ActiveForm::TYPE_FLOATING,
            'action' => ['article/create-in-course', 'course_id' => $course->id], // Specify the route to the create action
            'method' => 'post',
        ]); ?>
    
        <?= $form->errorSummary($model);?>
        <?= $form->field($model, 'title')->label(Yii::t('app', 'Title')) ?>
        <?= $form->field($model, 'description')->textarea(['rows' => 4, 'style' => 'min-height: 160px']) ?>
    
        <div class="w-100 text-center">
            <?= Html::button(Yii::t('app', 'Create Article'),['class' => ['btn btn-primary rotate_on_hover scale_on_hover mb-3'], 'type' => 'submit']) ?>
        </div>
    
        <?php ActiveForm::end(); ?>
    </div>
</div>