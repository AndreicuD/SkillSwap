<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = Yii::t('app', 'Privacy Policy');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about padd-15">
    <h1 style="text-align: center;" class="page_title"><?= Html::encode($this->title) ?></h1>

    <br>
    <b><?= Yii::t('app', 'Last updated: [June 24, 2025]')?></b>
    <hr>
    <?= Yii::t('app', 'This Privacy Policy explains how Skill Swap ("we", "our", or "us") collects, uses, and protects your personal information.')?>

    <ol>
        <h3><li><?= Yii::t('app', 'Information We Collect')?></li></h3>
            <?= Yii::t('app', 'When you create an account, we may collect:')?>
            <ul>
                <li><?= Yii::t('app', 'Your name or username')?></li>
                <li><?= Yii::t('app', 'Email address')?></li>
                <li><?= Yii::t('app', 'Activity on the platform (such as published content or reviews)')?></li>
            </ul>
            <?= Yii::t('app', 'We do not collect sensitive personal data unless explicitly required and disclosed.')?>

        <h3><li><?= Yii::t('app', 'How We Use Your Information')?></li></h3>
            <?= Yii::t('app', 'Your information is used for:')?>
            <ul>
                <li><?= Yii::t('app', 'Managing your account')?></li>
                <li><?= Yii::t('app', 'Enabling interactions with the platform')?></li>
                <li><?= Yii::t('app', 'Improving site performance and user experience')?></li>
            </ul>
            <?= Yii::t('app', 'We may also use non-personal data for analytics or performance monitoring.')?>

        <h3><li><?= Yii::t('app', 'Sharing of Information')?></li></h3>
            <?= Yii::t('app', 'We do not sell or rent your personal data to third parties.')?>
            <?= Yii::t('app', 'We may share data with trusted third-party services (e.g., analytics, hosting) strictly for platform functionality and only under data protection agreements.')?>

        <h3><li><?= Yii::t('app', 'Data Security')?></li></h3>
            <?= Yii::t('app', 'We use reasonable security measures to protect your information. However, no method of online transmission is 100% secure.')?>

        <h3><li><?= Yii::t('app', 'Your Rights')?></li></h3>
            <?= Yii::t('app', 'You have the right to:')?>
            <ul>
                <li><?= Yii::t('app', 'Request access to your personal data')?></li>
                <li><?= Yii::t('app', 'Request deletion of your account and related data')?></li>
                <li><?= Yii::t('app', 'Withdraw consent at any time')?></li>
            </ul>
            <?= Yii::t('app', 'For any requests, please contact us using the')?> <?= Html::a(Yii::t('app', 'Contact Us'),['/site/contact']) ?> <?= Yii::t('app', 'page') ?>

        <h3><li><?= Yii::t('app', 'Children\'s Privacy')?></li></h3>
            <?= Yii::t('app', 'Skill Swap is not intended for children under the age of 13. We do not knowingly collect data from minors.')?>

        <h3><li><?= Yii::t('app', 'Changes to This Policy')?></li></h3>
            <?= Yii::t('app', 'We may update this Privacy Policy from time to time. Significant changes will be communicated through the platform or via email.')?>
    </ol>
</div>
