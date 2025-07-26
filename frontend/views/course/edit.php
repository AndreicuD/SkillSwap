<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;
use common\models\Article;
use common\models\Category;
use kartik\select2\Select2;
use kartik\widgets\FileInput;
use common\models\Quiz;

$this->title = $model->title;
//$this->params['breadcrumbs'][] = $this->title;

$svg = [
    'quiz' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-help-hexagon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" /><path d="M12 16v.01" /><path d="M12 13a2 2 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" /></svg>',
    'article' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-news"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16 6h3a1 1 0 0 1 1 1v11a2 2 0 0 1 -4 0v-13a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1v12a3 3 0 0 0 3 3h11" /><path d="M8 8l4 0" /><path d="M8 12l4 0" /><path d="M8 16l4 0" /></svg>'
];
$trash_svg = '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="1.5"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>';
$sortable_svg = '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="1"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-menu-order"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 10h16" /><path d="M4 14h16" /><path d="M9 18l3 3l3 -3" /><path d="M9 6l3 -3l3 3" /></svg>';

$this->registerJsFile('https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js', [
    'position' => View::POS_END,
]);

$sortable_update_url = Url::to(['course/update-sort-order', 'course_id' => $model->public_id]);

$sortable_js = <<<JS
const courseList = document.getElementById('course-elements-list');

Sortable.create(courseList, {
    handle: '.sort-handler',
    animation: 150,
    onEnd: function (/**Event*/evt) {
        const rows = document.querySelectorAll('#course-elements-list .element-row');
        const order = [];

        rows.forEach((row, index) => {
            order.push({
                id: row.dataset.elementId,
                sort_index: index + 1
            });
        });

        // Send the new order to server via AJAX
        $.ajax({
            type: 'POST',
            url: '$sortable_update_url',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                order: order
            },
            success: function(response) {
                console.log("Sort order saved");
            },
            error: function(xhr) {
                alert('Failed to save sort order');
            }
        });
    }
});
JS;
$this->registerJs($sortable_js, View::POS_END);

?>
<div class="site-index">
    <h1 style="text-align: center;" class="page_title"><?= Html::encode($this->title) ?></h1>
    
    <br>

    <div class="group_together">
        <div class="w-80 course-left">
            <div class="w-100 flex-row-even">
                <?= $this->render('/course/add_element_card', [
                    'type' => 'article',
                    'title' => 'Add an article',
                    'url' => ['/article/create-in-course', 'course_id' => $model->id],
                ]); ?>
                <?= $this->render('/course/add_element_card', [
                    'type' => 'quiz',
                    'title' => 'Add a quiz',
                    'url' => ['/quiz/create', 'course_id' => $model->id],
                ]); ?>
            </div> 
            
        
            <table class="table sortable-table ">
                <thead class="course_table_head">
                    <tr>
                        <th class="course_element_id">#</th>
                        <th class="course_element_type"><?= Yii::t('app', 'Type')?></th>
                        <th><?= Yii::t('app', 'Title')?></th>
                        <th class="course_element_actions"><?= Yii::t('app', 'Actions')?></th>
                    </tr>
                </thead>
                <tbody id="course-elements-list">
                    <?php if (!empty($model->orderedElements)) { ?>
                        <?php $position = 1; ?>
                        <?php foreach ($model->orderedElements as $element_id => $element): ?>
                            <tr class="element-row" data-element-id="<?= $element_id ?>">
                                <td><div class="sort-handler d-flex"><?= $sortable_svg ?> <?= $position; ?></div></td>
                                <td>
                                    <?= $svg[$element['type']]?>
                                </td>
                                <td><?= Html::encode($element['model']->title ?? 'Untitled') ?></td>
                                <td>
                                    <?php if ($element['type'] === 'article'): ?>
                                        <?= Html::a('Edit', ['/article/course-edit', 'public_id' => $element['model']->public_id, 'course_id' => $model->public_id], ['class' => 'btn btn-primary']) ?>
                                        <button 
                                            id="article_modal_<?= $model->public_id ?>" 
                                            class="btn btn-danger btn-ajax" 
                                            data-modal_title='<?=Yii::t("app", "Delete"); ?> "<?=$element['model']->title?>"'
                                            data-modal_url="<?=Url::to(['article/ajax-delete', 'public_id' => $element['model']->public_id]); ?>" >
                                            <?=$trash_svg?>
                                        </button>
                                    <?php elseif ($element['type'] === 'quiz'): ?>
                                        <?= Html::a('Edit', ['/quiz/edit', 'public_id' => $element['model']->public_id, 'course_id' => $model->public_id], ['class' => 'btn btn-primary']) ?>
                                        <button 
                                            id="article_modal_<?= $model->public_id ?>" 
                                            class="btn btn-danger btn-ajax" 
                                            data-modal_title='<?=Yii::t("app", "Delete"); ?> "<?=$element['model']->title?>"'
                                            data-modal_url="<?=Url::to(['quiz/ajax-delete', 'public_id' => $element['model']->public_id, 'course_id' => $model->public_id]); ?>" >
                                            <?=$trash_svg?>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php $position++; ?>
                        <?php endforeach; ?>
                    <?php } else { ?>
                        <p class="note"><?= Yii::t('app', 'There are no elements defined for this course') ?></p>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="w-20">
            <?php $form = ActiveForm::begin([
                'id' => 'course-form',
                'type' => ActiveForm::TYPE_VERTICAL,
                'action' => ['course/update', 'id' => $model->id], // Specify the route to the create action
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
                    'dropdownParent' => '#course-form',
                ],
            ]); ?>

            <?= $form->field($model, 'is_public')->checkbox([
                'uncheck' => '0',
                'value' => '1',
            ])->label(Yii::t('app', 'Make Public')); ?>

            <?= $form->field($model, 'cover')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*'],
                'pluginOptions' => [
                    'showPreview' => false,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => false
                ]
            ]); ?>

            <div class="group_together">
                <div style="width: 100%; text-align: center;">
                    <?= Html::a(Yii::t('app', 'Go Back'),['/user/courses'],['class' => ['btn btn-outline-danger rotate_on_hover mb-3']]) ?>
                </div>
                <div style="width: 100%; text-align: center;">
                    <?= Html::button(Yii::t('app', 'Save'),['class' => ['btn btn-primary rotate_on_hover scale_on_hover mb-3'], 'type' => 'submit']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    

    <!-- Article Modal -->
    <div class="modal fade" id="article-blank" tabindex="-1" aria-labelledby="article_title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 truncate" id="article_title">Blank Title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$string_error = Yii::t('app', 'There was an error loading the data');

$update_js = <<< JS
const bootstrap_modal = new bootstrap.Modal('#article-blank');
const modal_element = $('#article-blank');
const text_waiting = '<div class="d-flex justify-content-center align-items-center p-2"><svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><style>.spinner_Uvk8{animation:spinner_otJF 1.6s cubic-bezier(.52,.6,.25,.99) infinite}.spinner_ypeD{animation-delay:.2s}.spinner_y0Rj{animation-delay:.4s}@keyframes spinner_otJF{0%{transform:translate(12px,12px) scale(0);opacity:1}75%,100%{transform:translate(0,0) scale(1);opacity:0}}</style><path class="spinner_Uvk8" d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,20a9,9,0,1,1,9-9A9,9,0,0,1,12,21Z" transform="translate(12, 12) scale(0)"/><path class="spinner_Uvk8 spinner_ypeD" d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,20a9,9,0,1,1,9-9A9,9,0,0,1,12,21Z" transform="translate(12, 12) scale(0)"/><path class="spinner_Uvk8 spinner_y0Rj" d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,20a9,9,0,1,1,9-9A9,9,0,0,1,12,21Z" transform="translate(12, 12) scale(0)"/></svg></div>';
$(document).ready(function () {
    $(document).on('click', '.btn-ajax', function(){
        let request_url = $(this).data('modal_url');
        modal_element.find('.modal-title').html($(this).data('modal_title'));
        modal_element.find('.modal-body').html(text_waiting).load(request_url, function( response, status, xhr ) {
            if ( status === "error" ) {
                let text_error = '<p class="alert alert-danger">$string_error<br>'
                text_error += xhr.status + " " + xhr.statusText + '</p>';
                modal_element.find('.modal-body').html(text_error);
            }
        });
        bootstrap_modal.show();
    });
    
    $('#article-blank .btn-close').on('click', function(){
        bootstrap_modal.hide();
    });
});
JS;
$this->registerJs($update_js, View::POS_END); ?>