<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = Yii::t('app', 'Frequently Asked Questions');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about padd-15">
    <h1 style="text-align: center;" class="page_title"><?= Html::encode($this->title) ?></h1>
    
    <div class="accordion" style="padding-top: 15vh;" id="faq">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <b><?= Yii::t('app', 'Do I Need an Account?')?></b>
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faq">
                <div class="accordion-body">
                    <?= Yii::t('app', '<b>Yes.</b> To interact with the platform - whether it’s to read or publish articles, leave reviews, enroll in courses, or earn coins you’ll need to create an account. This helps us keep track of your progress, purchases, and rewards.')?>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    <b><?= Yii::t('app', 'How do you earn points?')?></b>
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faq">
                <div class="accordion-body">
                    <?= Yii::t('app', 'Skill Swap uses a <b>virtual coin system</b> instead of real money. You earn coins by publishing useful articles or courses that others buy, or by engaging with the platform (such as receiving <b>daily login bonuses</b> or <b>leaving your first review</b> on purchased content). You get <b>20% of the value</b> when your article is purchased.')?>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    <b><?= Yii::t('app', 'What are the differences between courses and articles?')?></b>
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faq">
                <div class="accordion-body">
                    <?= Yii::t('app', 'Articles are stand-alone pieces of content that cover a specific topic or skill. Courses, on the other hand, are structured series of articles that include quizzes, an exam, and a <b>certificate upon completion</b>. Courses provide a more in-depth and guided learning experience.')?>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    <b><?= Yii::t('app', 'Is Skill Swap free to use?')?></b>
                </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#faq">
                <div class="accordion-body">
                    <?= Yii::t('app', '<b>Yes, Skill Swap is completely free to use.</b> You don’t need to pay real money to access the platform. Instead, we use a virtual coin system. You can earn coins daily just by logging in, by writing articles or courses, or by participating in the community (like leaving helpful reviews). These coins can then be used to access content from other users.')?>
                </div>
            </div>
        </div>
    </div>        

</div>
