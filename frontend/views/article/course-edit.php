<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\editors\Summernote;
use kartik\editors\Codemirror;
use kartik\form\ActiveForm;
use common\models\Category;
use kartik\select2\Select2;
use kartik\widgets\FileInput;

$this->title = $model->title;
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1 style="text-align: center;" class="page_title"><?= Html::encode($this->title) ?></h1>

    <br>

    <?php $form = ActiveForm::begin([
        'id' => 'article-form-course',
        'type' => ActiveForm::TYPE_VERTICAL,
        'action' => ['article/update', 'id' => $model->id, 'page' => 'course-edit', 'course_id' => $course_id], // Specify the route to the create action
        'method' => 'post',
    ]); ?>

    <?= $form->errorSummary($model);?>

    <div class=group_together>
        <div class="w-80">
            <?= Summernote::widget([
                'name' => Html::getInputName($model, 'content'),
                'value' => Html::getAttributeValue($model, 'content'),
                'options' => ['id' => Html::getInputId($model, 'content'), 'class' => 'form-control'],
                'useKrajeePresets' => true,
                'pluginOptions' => [
                    'height' => 500,
                    'dialogsFade' => true,
                    'toolbar' => [
                        ['style1', ['style']],
                        ['style2', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript']],
                        ['font', ['fontsize', 'color', 'clear']],
                        ['para', ['ul', 'ol', 'paragraph', 'height']],
                        ['insert', ['link', 'picture', 'video', 'table', 'hr']],
                    ],
                    'fontSizes' => ['8', '9', '10', '11', '12', '13', '14', '16', '18', '20', '24', '36', '48'],
                ],
            ]);?>
        </div>
        <div class="w-20">
            <?= $form->errorSummary($model);?>
            <?= $form->field($model, 'title')->label(Yii::t('app', 'Title')) ?>
            <?= $form->field($model, 'description')->textarea(['rows' => 4, 'style' => 'min-height: 160px']) ?>
        </div>
    </div>

    <br>
    <div class="group_together">
        <div style="width: 100%; text-align: center;">
            <?= Html::a(Yii::t('app', 'Go Back'),['/course/edit', 'public_id' => $course_id],['class' => ['btn btn-outline-danger rotate_on_hover mb-3']]) ?>
        </div>
        <div style="width: 100%; text-align: center;">
            <?= Html::button(Yii::t('app', 'Submit Changes'),['class' => ['btn btn-primary rotate_on_hover scale_on_hover mb-3'], 'type' => 'submit']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>