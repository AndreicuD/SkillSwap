<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\web\View;

$this->title = Yii::t('app', 'Courses Bought');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1 style="text-align: center;" class="page_title"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('/templates/search', [
        'model' => $model,
        'url' => '/article/index'
    ]) ?>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '/templates/course-bought',
        'viewParams' => ['transactionModel' => $transactionModel,
                        'reviewModel' => $reviewModel,
                        'bookmarkModel' => $courseBookmarkModel,
                        'page' => 'course/index',
                    ],
        'options' => [
            'tag' => 'div',
            'class' => 'flex-row-start'
        ],
        'itemOptions' => [
            'tag' => 'div',
            'class' => 'card',
        ],
        'layout' => '{items}{pager}',
        'pager' => [
            'pageCssClass' => 'page-item',
            'prevPageCssClass' => 'prev page-item',
            'nextPageCssClass' => 'next page-item',
            'firstPageCssClass' => 'first page-item',
            'lastPageCssClass' => 'last page-item',
            'linkOptions' => ['class' => 'page-link'],
            'disabledListItemSubTagOptions' => ['class' => 'page-link'],
            'options' => ['class' => 'pagination justify-content-center'],
        ],
    ]); ?>

    <!-- Course Modal -->
    <div class="modal fade" id="course-blank" tabindex="-1" aria-labelledby="course_title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="course_title">Blank Title</h1>
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
const bootstrap_modal = new bootstrap.Modal('#course-blank');
const modal_element = $('#course-blank');
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
    
    $('#course-blank .btn-close').on('click', function(){
        bootstrap_modal.hide();
    });
});
JS;
$this->registerJs($update_js, View::POS_END); ?>
