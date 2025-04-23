<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\editors\Summernote;
use kartik\editors\Codemirror;
use kartik\form\ActiveForm;
use common\models\Category;
use kartik\select2\Select2;

$this->title = $model->title;
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1 style="text-align: center;" class="page_title"><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'article-form',
        'type' => ActiveForm::TYPE_VERTICAL,
        'action' => ['article/update', 'id' => $model->id, 'page' => 'edit'], // Specify the route to the create action
        'method' => 'post',
    ]); ?>

    <?= $form->errorSummary($model);?>

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

    <br>
    <div class="group_together">
        <div style="width: 100%; text-align: center;">
            <?= Html::a(Yii::t('app', 'Go Back'),['/user/articles'],['class' => ['btn btn-outline-danger rotate_on_hover mb-3']]) ?>
        </div>
        <div style="width: 100%; text-align: center;">
            <?= Html::button(Yii::t('app', 'Settings'),
            ['class' => ['btn btn-secondary rotate_on_hover mb-3'],
                'type' => 'button',
                'data-bs-toggle' => 'modal',
                'data-bs-target' => '#article-edit' ]) ?>
        </div>
        <div style="width: 100%; text-align: center;">
            <?= Html::button(Yii::t('app', 'Submit Changes'),['class' => ['btn btn-primary rotate_on_hover scale_on_hover mb-3'], 'type' => 'submit']) ?>
        </div>
    </div>
    
    <!-- Edit Settings of Article Modal -->
    <div class="modal fade" id="article-edit" tabindex="-1" aria-labelledby="article_title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 truncate" id="article_title"><?= Yii::t('app', 'Settings') ?></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                </div>

                <div class="modal-footer">
                    <?= Html::button(Yii::t('app', 'Submit Changes'),['class' => ['btn btn-primary rotate_on_hover scale_on_hover mb-3'], 'type' => 'submit']) ?>
                </div>

            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>