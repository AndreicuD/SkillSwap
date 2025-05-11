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
    <div class="index-about">
        <div class="group_together padd-15">
            <div class="w-50">
                <h1>ceva</h1>
            </div>
            <div class="w-50">
                <h1>test</h1>
            </div>
        </div>
    </div>
    <div class="padd-15">
        <div class="index-description title">
            <p class="index-description-text"><?= Yii::t('app', 'Every day is an oportunity to learn! Here you can learn anything from anyone, anyday. Come join us now!') ?></p>
        </div>
        <div id="carouselCourses" class="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="card" style="width: 17rem;">
                        <h5 class="card-header"><?= Yii::t('app', 'Course 1') ?></h5>
                        <div class="card-body">
                            <p class="card-text"><?= Yii::t('app', 'Some quick example text to build on the card title and make up the bulk of the cards content') ?>.</p>
                            <a href="#" class="btn btn-primary scale_on_hover rotate_on_hover"><?= Yii::t('app', 'Buy now!') ?></a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="card" style="width: 17rem;">
                        <h5 class="card-header"><?= Yii::t('app', 'Course 2') ?></h5>
                        <div class="card-body">
                            <p class="card-text"><?= Yii::t('app', 'Some quick example text to build on the card title and make up the bulk of the cards content') ?>.</p>
                            <a href="#" class="btn btn-primary scale_on_hover rotate_on_hover"><?= Yii::t('app', 'Buy now!') ?></a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="card" style="width: 17rem;">
                        <h5 class="card-header"><?= Yii::t('app', 'Course 3') ?></h5>
                        <div class="card-body">
                            <p class="card-text"><?= Yii::t('app', 'Some quick example text to build on the card title and make up the bulk of the cards content') ?>.</p>
                            <a href="#" class="btn btn-primary scale_on_hover rotate_on_hover"><?= Yii::t('app', 'Buy now!') ?></a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="card" style="width: 17rem;">
                        <h5 class="card-header"><?= Yii::t('app', 'Course 4') ?></h5>
                        <div class="card-body">
                            <p class="card-text"><?= Yii::t('app', 'Some quick example text to build on the card title and make up the bulk of the cards content') ?>.</p>
                            <a href="#" class="btn btn-primary scale_on_hover rotate_on_hover"><?= Yii::t('app', 'Buy now!') ?></a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="card" style="width: 17rem;">
                        <h5 class="card-header"><?= Yii::t('app', 'Course 5') ?></h5>
                        <div class="card-body">
                            <p class="card-text"><?= Yii::t('app', 'Some quick example text to build on the card title and make up the bulk of the cards content') ?>.</p>
                            <a href="#" class="btn btn-primary scale_on_hover rotate_on_hover"><?= Yii::t('app', 'Buy now!') ?></a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="card" style="width: 17rem;">
                        <h5 class="card-header"><?= Yii::t('app', 'Course 6') ?></h5>
                        <div class="card-body">
                            <p class="card-text"><?= Yii::t('app', 'Some quick example text to build on the card title and make up the bulk of the cards content') ?>.</p>
                            <a href="#" class="btn btn-primary scale_on_hover rotate_on_hover"><?= Yii::t('app', 'Buy now!') ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            </div>
        <div class="title">
            <h2><?= Yii::t('app', 'Trending articles') ?></h2>
        </div>
    </div>
</div>
