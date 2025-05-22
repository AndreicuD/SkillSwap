<?php

/** @var yii\web\View $this */
/* @var $latestDataProvider yii\data\ActiveDataProvider */

use yii\bootstrap5\Html;
use yii\widgets\ListView;
use yii\web\View;
use yii\helpers\Url;

$this->title = 'Skill Swap';
?>
<div class="hero user-select-none">
    <div class="hero-text" id="hero-text">
        <h1>Skill Swap</h1>
        <p><?= Yii::t('app', 'Discover your new hobby!') ?></p>
        <div class="btn-group" role="group">
            <?php 
                if (Yii::$app->user->isGuest){
                    echo Html::a(Yii::t('app', 'Join Us'),['/user/signup'],['class' => ['btn btn-secondary btn-lg']]);
                }
                else {
                    //echo Html::a(Yii::t('app', 'Explore Courses'),['/course/index'],['class' => ['btn btn-secondary btn-lg']]);
                    echo Html::a(Yii::t('app', 'Explore Articles'),['/article/index'],['class' => ['btn btn-secondary btn-lg']]);
                }
            ?> 
        </div>
    </div>
    <div class="hero-arrow-anim">
        <a class="hero-arrow" href="#site-index">
            <svg xmlns="http://www.w3.org/2000/svg"  width="60"  height="60"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-compact-down down-arrow"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 11l8 3l8 -3" /></svg>
        </a>
    </div>
</div>

<div class="index-description text-center">
    <p class="index-description-text lead"><?= Yii::t('app', 'Every day is an oportunity to learn! Here you can learn anything from anyone, anyday. Come join us now!') ?></p>
</div>
<div class="site-index" id="site-index">
    <div class="padd-15">
        <div class="why-div">
            <div class="title why-text"><?= Yii::t('app','Why choose us?') ?></div>
            <div class="flex-row-even">
                <div class="card column">
                    <div class="card-header text-center">
                        <h5><?= Yii::t('app','To learn') ?></h5>
                    </div>
                    <div class="card-body">
                        <p><?= Yii::t('app','We wanted to make learning accesible to anyone, no matter who you are and what you do.')?></p>
                    </div>
                </div>
                <div class="card column">
                    <div class="card-header text-center">
                        <h5><?= Yii::t('app','Fun') ?></h5>
                    </div>
                    <div class="card-body">
                        <p><?= Yii::t('app','You can learn new stuff by buying articles, which contain information on whatever you want to study.')?></p>
                    </div>
                </div>
                <div class="card column">
                    <div class="card-header text-center">
                        <h5><?= Yii::t('app','Easy to use') ?></h5>
                    </div>
                    <div class="card-body">
                        <p><?= Yii::t('app','We managed to create an intuitive point-based system that allows you to buy articles using points.')?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<br>
<hr>
<br>

<div class="padd-15">
    <h1 class="title"><?= Yii::t('app', 'Latest Articles') ?></h1>
    <?= ListView::widget([
        'dataProvider' => $latestDataProvider,
        'itemView' => '/templates/article',
        'viewParams' => ['transactionModel' => $transactionModel,
                        'reviewModel' => $reviewModel,
                        'bookmarkModel' => $bookmarkModel,
                        'page' => 'article/index',
                    ],
        'options' => [
            'tag' => 'div',
            'class' => 'flex-row-even'
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
</div>
<br>
<div class="padd-15">
    <h1 class="title"><?= Yii::t('app', 'Top Rated Articles') ?></h1>
    <?= ListView::widget([
        'dataProvider' => $topRatedDataProvider,
        'itemView' => '/templates/article',
        'viewParams' => ['transactionModel' => $transactionModel,
                        'reviewModel' => $reviewModel,
                        'bookmarkModel' => $bookmarkModel,
                        'page' => 'article/index',
                    ],
        'options' => [
            'tag' => 'div',
            'class' => 'flex-row-even'
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
    $this->registerJs($update_js, View::POS_END);
?>

<br>
<div class="about-us">
    <div class="padd-15">
        <div class="title">
            <h1><?= Yii::t('app', 'About us')?></h1>
        </div>
        <div class="about-us-text">
            <div><?=Yii::t('app', 'We are 2 students that aspire to do good things to help people online. We managed to create this site, hoping to help people who want to learn things fast.')?></div>
        </div>
    </div>
</div>