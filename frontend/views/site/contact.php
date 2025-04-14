<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\ContactForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('app', 'Contact Us');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1 class="page_title" style="text-align: center;"><?= Html::encode($this->title) ?></h1>

    <p class="text-center lead">
        <?= Yii::t("app", "For any questions please don't hesitate to contact us using the form below.") ?>
    </p>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <?php $form = ActiveForm::begin(['id' => 'contact-form', 'layout' => 'floating']); ?>

                <?= $form->field($model, 'name')->textInput(['autofocus' => true])->label(Yii::t('app', 'Nume')) ?>

                <?= $form->field($model, 'email')->label(Yii::t('app', 'Email')) ?>

                <?= $form->field($model, 'subject')->label(Yii::t('app', 'Subiect')) ?>

                <?= $form->field($model, 'body')->textarea(['rows' => 6, 'style' => 'min-height: 160px'])->label(Yii::t('app', 'Ce vrei sa ne spui?')) ?>

                <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                    'template' => '<div class="row"><div class="col-lg-3 justify" style="text-align: center;">{image}</div><div style = "padding-top: 1rem;" class="col-lg-6">{input}</div></div>',
                ])->label(false); ?>

                <br>
                <div class="form-group" style="text-align: center;">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>
                <br>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-lg-6 contact_map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2847.7856380972617!2d26.077534076587835!3d44.45806660000382!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40b202033db033df%3A0x1cb72daf776fb289!2sColegiul%20Na%C8%9Bional%20International%20de%20Informatic%C4%83%20%E2%80%9CTudor%20Vianu%22!5e0!3m2!1sro!2sro!4v1720683454364!5m2!1sro!2sro" width="600" height="450" style="border:0;" allowfullscreen="true" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>

</div>
