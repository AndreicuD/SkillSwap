<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = Yii::t('app', 'Terms and Conditions');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about padd-15">
    <h1 style="text-align: center;" class="page_title"><?= Html::encode($this->title) ?></h1>

    <br>
    <b><?= Yii::t('app', 'Last updated: [June 24, 2025]')?></b>
    <hr>
    <?= Yii::t('app', 'Welcome to Skill Swap. By accessing or using our website (the “Platform”), you agree to be bound by these Terms of Service (“Terms”). Please read them carefully.')?>
    <ol>
        <h3><li><?= Yii::t('app', 'Acceptance of Terms')?></li></h3>
            <?= Yii::t('app', 'By creating an account or using any part of the Platform, you agree to comply with and be bound by these Terms.')?>

        <h3><li><?= Yii::t('app', 'Use of the Platform')?></li></h3>
            <?= Yii::t('app', 'You agree not to misuse the Platform. This includes, but is not limited to:')?>
            <ul>
                <li><?= Yii::t('app', 'Posting or distributing any content that is illegal, defamatory, abusive, or plagiarized.')?></li>
                <li><?= Yii::t('app', 'Attempting to interfere with or compromise the integrity or security of the platform.')?></li>
                <li><?= Yii::t('app', 'Using the Platform for unauthorized commercial purposes.')?></li>
            </ul>

        <h3><li><?= Yii::t('app', 'User Content')?></li></h3>
            <?= Yii::t('app', 'All content you upload remains your intellectual property. However, by publishing content on Skill Swap, you grant us a non-exclusive, worldwide, royalty-free license to use, host, and display that content as part of the platform’s functionality.')?>
            <?= Yii::t('app', 'We reserve the right to remove any content or suspend user accounts that violate these Terms.')?>
        
        <h3><li><?= Yii::t('app', 'Account Termination')?></li></h3>
            <?= Yii::t('app', 'We may suspend or terminate your access to the Platform at any time if you violate these Terms, or if we are required to do so by law.')?>
        
        <h3><li><?= Yii::t('app', 'Changes to the Terms')?></li></h3>
            <?= Yii::t('app', 'We reserve the right to modify these Terms at any time. Continued use of the Platform after changes have been posted constitutes your acceptance of the new Terms.')?>
    </ol>
</div>
