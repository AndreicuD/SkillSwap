<?php 
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\Category;
?>
<?php $form = ActiveForm::begin([
    'id' => 'form-searcharticle',
    'type' => ActiveForm::TYPE_INLINE,
    'method' => 'get',
]); ?>

    <?= $form->errorSummary($model);?>
    <?= Html::a(Yii::t('app', 'Reset'),[$url],['class' => ['btn btn-outline-danger rotate_on_hover mb-3']]) ?>

    <?= $form->field($model, 'category_name')->widget(Select2::class, [
        'data' => Category::getCategories(),
        'options' => [
            'placeholder' => Yii::t('app', 'Category'),
        ],
        'pluginOptions' => [
            'tags' => true, // allow custom values
            'allowClear' => true,
            'dropdownParent' => '#form-searcharticle',
        ],
    ]); ?>
    <?= $form->field($model, 'title')->label(Yii::t('app', 'Title')) ?>

    <?= Html::button(Yii::t('app', 'Search'),['class' => ['btn btn-primary rotate_on_hover mb-3'], 'type' => 'submit']) ?>
    

<?php ActiveForm::end(); ?>