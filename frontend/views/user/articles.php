<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\widgets\ListView;
use kartik\widgets\ActiveForm;
use yii\bootstrap5\Modal;
use yii\web\View;
use common\models\Category;
use kartik\select2\Select2;

$this->title = Yii::t('app', 'My Articles');
//$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    @media(max-width: 767px) {
        .new-article-button {
            text-align: center;
            width: 100%;
        }
    }
</style>
<div class="site-index">
    <h1 style="text-align: center;" class="page_title"><?= Html::encode($this->title) ?></h1>

    <div class="group_together">
        <div class="new-article-button">
            <?= Html::a(Yii::t('app', 'Create New Article'),['/article/create'],['class' => ['btn btn-secondary rotate_on_hover scale_on_hover mb-3']]) ?>
        </div>
    
        <?= $this->render('/templates/search', [
            'model' => $model,
            'url' => '/user/articles'
        ]) ?>
    </div>


    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_article',
        'viewParams' => [],
        'options' => [
            'tag' => 'div',
            //'class' => 'flex-row-start card-slide'
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