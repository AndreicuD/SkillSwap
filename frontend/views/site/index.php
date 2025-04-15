<?php

/** @var yii\web\View $this */
/* @var $latestDataProvider yii\data\ActiveDataProvider */

use yii\bootstrap5\Html;
use yii\widgets\ListView;
use yii\helpers\Url;

$this->title = 'Skill Swap';
?>
<d class="site-index">
    <div class="container my-3 hero hero-image">
        <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
            <div class="col-lg-6">
                <h1 class="display-5 fw-bold lh-1 mb-3 page_title">SkillSwap</h1>
                <p class="lead lh-1"><?= Yii::t('app', 'Are you ready to start learning? etc etc etc.') ?></p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                    <button type="button" class="btn btn-primary"><?= Yii::t('app', 'Get Started') ?></button>
                </div>
            </div>
        </div>
    </div>


    <div style="padding: 0 16%;">
        <div class="index-description title">
            <p class="index-description-text"><?= Yii::t('app', 'Every day is an oportunity to learn! Here you can learn anything from anyone, anyday. Come join us now!') ?></p>
        </div>
        <div class="card-flex">
            <div class="card" style="width: 17rem;">
                <h5 class="card-header"><?= Yii::t('app', 'Course 1') ?></h5>
                <div class="card-body">
                    <p class="card-text"><?= Yii::t('app', 'Some quick example text to build on the card title and make up the bulk of the cards content') ?>.</p>
                    <a href="#" class="btn btn-primary"><?= Yii::t('app', 'Buy now!') ?></a>
                </div>
            </div>
            <div class="card" style="width: 17rem;">
                <h5 class="card-header"><?= Yii::t('app', 'Course 2') ?></h5>
                <div class="card-body">
                    <p class="card-text"><?= Yii::t('app', 'Some quick example text to build on the card title and make up the bulk of the cards content') ?>.</p>
                    <a href="#" class="btn btn-primary"><?= Yii::t('app', 'Buy now!') ?></a>
                </div>
            </div>
            <div class="card" style="width: 17rem;">
                <h5 class="card-header"><?= Yii::t('app', 'Course 3') ?></h5>
                <div class="card-body">
                    <p class="card-text"><?= Yii::t('app', 'Some quick example text to build on the card title and make up the bulk of the cards content') ?>.</p>
                    <a href="#" class="btn btn-primary"><?= Yii::t('app', 'Buy now!') ?></a>
                </div>
            </div>
        </div>
        <div class="title">
            <h2><?= Yii::t('app', 'Trending articles') ?></h2>
        </div>
    </div>
</div>
