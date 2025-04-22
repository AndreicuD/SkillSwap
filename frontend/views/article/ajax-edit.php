<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var common\models\Article $model */

use kartik\widgets\ActiveForm;
use yii\bootstrap5\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

use common\models\Category;
$model->category_name = Category::getName($model->category);
?>
<?php $form = ActiveForm::begin([
    'id' => 'article-form',
    'type' => ActiveForm::TYPE_FLOATING,
    'action' => ['article/update', 'id' => $model->id, 'page' => 'user'], // Specify the route to the create action
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

<?php ActiveForm::end(); ?>
<hr>
<p class="card-text text-center" style="margin-bottom: 0; align-content: center;"><b><?= Yii::t('app', 'How many bought: ') ?></b></p>
<p class="custom-value custom-bought text-center"><?= Html::encode($model->bought) ?></p>
<p class="card-text" style="margin-bottom: 0; align-content: center;"><b><?= Yii::t('app', 'Last Updated At: ') ?></b> <?= Html::encode($model->updated_at) ?></p>
<p class="card-text" style="margin-bottom: 0; align-content: center;"><b><?= Yii::t('app', 'Created At: ') ?></b> <?= Html::encode($model->created_at) ?></p>