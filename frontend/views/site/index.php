<?php

/** @var yii\web\View $this */
/* @var $latestDataProvider yii\data\ActiveDataProvider */

use yii\bootstrap5\Html;
use yii\widgets\ListView;
use yii\helpers\Url;

$this->title = 'Skill Swap';
?>
<div class="site-index">
    <div class="container my-3 hero hero-image">
        <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
            <div class="col-lg-6">
                <h1 class="display-5 fw-bold lh-1 mb-3 page_title">SkillSwap</h1>
                <p class="lead lh-1"><?= Yii::t('app', 'Are you ready to start learning? etc etc etc.') ?></p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                    <button type="button" class="btn btn-primary">Get Started</button>
                </div>
            </div>
        </div>
    </div>
</div>
