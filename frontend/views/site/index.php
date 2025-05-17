<?php

/** @var yii\web\View $this */
/* @var $latestDataProvider yii\data\ActiveDataProvider */

use yii\bootstrap5\Html;
use yii\widgets\ListView;
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

<div class="site-index" id="site-index">
    <div class="padd-15">
        <div class="index-description title">
            <p class="index-description-text"><?= Yii::t('app', 'Every day is an oportunity to learn! Here you can learn anything from anyone, anyday. Come join us now!') ?></p>
        </div>
        <div class="why-div">
            <div class="title why-text"><?= Yii::t('app','Why choose us?') ?></div>
            <div class="card-flex">
                <div class="card column why-card">
                    <div class="card-header text-center">
                        <h5><?= Yii::t('app','Reason no.1') ?></h5>
                    </div>
                    <div class="card-body">
                        <p><?= Yii::t('app','We wanted to make learning accessible to everyone. For this reason, we designed this platform in order to ensure you learn whatever you want as fast as possible. We also want to build a community with people that help each other and make the experience better.')?></p>
                    </div>
                </div>
                <div class="card column why-card">
                    <div class="card-header text-center">
                        <h5><?= Yii::t('app','Reason no.2') ?></h5>
                    </div>
                    <div class="card-body">
                        <p><?= Yii::t('app','We wanted to make learning accessible to everyone. For this reason, we designed this platform in order to ensure you learn whatever you want as fast as possible. We also want to build a community with people that help each other and make the experience better.')?></p>
                    </div>
                </div>
                <div class="card column why-card">
                    <div class="card-header text-center">
                        <h5><?= Yii::t('app','Reason no.3') ?></h5>
                    </div>
                    <div class="card-body">
                        <p><?= Yii::t('app','We wanted to make learning accessible to everyone. For this reason, we designed this platform in order to ensure you learn whatever you want as fast as possible. We also want to build a community with people that help each other and make the experience better.')?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="trending-articles">
            <div class="title trend-text"> <?=Yii::t('app','Trending articles')?></div>
            <div class="trending-articles-flex">
                <div class="card trend-art" style="width: 18rem;">
                        <img src="../frontend/web/img/placeholder.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
                <div class="card trend-art" style="width: 18rem;">
                        <img src="../frontend/web/img/placeholder.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
                <div class="card trend-art" style="width: 18rem;">
                        <img src="../frontend/web/img/placeholder.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>