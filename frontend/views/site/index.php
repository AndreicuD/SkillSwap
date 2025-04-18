<?php

/** @var yii\web\View $this */
/* @var $latestDataProvider yii\data\ActiveDataProvider */

use yii\bootstrap5\Html;
use yii\widgets\ListView;
use yii\helpers\Url;

$this->title = 'Skill Swap';
?>
<div class="site-index">

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
