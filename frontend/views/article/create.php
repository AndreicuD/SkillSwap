<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\widgets\ListView;
use kartik\widgets\ActiveForm;
use yii\bootstrap5\Modal;
use yii\web\View;
use common\models\Category;
use kartik\select2\Select2;

$this->title = Yii::t('app', 'Create New Article');
//$this->params['breadcrumbs'][] = $this->title;
$model->price = 1000;
?>
<div class="site-index">
    <h1 style="text-align: center;" class="page_title"><?= Html::encode($this->title) ?></h1>

    <div class="padd-15">
        <?php $form = ActiveForm::begin([
            'id' => 'article-form',
            'type' => ActiveForm::TYPE_FLOATING,
            'action' => ['article/create'], // Specify the route to the create action
            'method' => 'post',
        ]); ?>
    
        <?= $form->errorSummary($model);?>
        <?= $form->field($model, 'title')->label(Yii::t('app', 'Title')) ?>
        <?= $form->field($model, 'description')->textarea(['rows' => 4, 'style' => 'min-height: 160px']) ?>
    
        <?= $form->field($model, 'price')->label(Yii::t('app', 'Price')) ?>
    
        <?= $form->field($model, 'category_name')->widget(Select2::class, [
            'data' => Category::getCategories(),
            'options' => [
                'placeholder' => Yii::t('app', 'Category'),
            ],
            'pluginOptions' => [
                'tags' => true, // allow custom values
                'allowClear' => true,
                'dropdownParent' => '#article-form',
            ],
        ]); ?>
    
        <?= $form->field($model, 'is_public')->checkbox([
            'uncheck' => '0',
            'value' => '1',
        ])->label(Yii::t('app', 'Make Public')); ?>
    
        <div class="w-100 text-center">
            <?= Html::button(Yii::t('app', 'Create Article'),['class' => ['btn btn-primary rotate_on_hover scale_on_hover mb-3'], 'type' => 'submit']) ?>
        </div>
    
        <?php ActiveForm::end(); ?>
    </div>
</div>