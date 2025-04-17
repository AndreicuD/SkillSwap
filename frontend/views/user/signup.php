<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = Yii::t('app', 'Signup');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1 class="text-center page_title"><?= Html::encode($this->title) ?></h1>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <?php $form = ActiveForm::begin(['id' => 'form-signup', 'layout' => 'floating']); ?>

            <?= $form->field($model, 'firstname')->label(Yii::t('app', 'First Name')) ?>
            <?= $form->field($model, 'lastname')->label(Yii::t('app', 'Last Name')) ?>
            <hr>
            <?= $form->field($model, 'email')->label(Yii::t('app', 'Email')) ?>
            <?= $form->field($model, 'password')->passwordInput()->label(Yii::t('app', 'Password')) ?>

                <br>
                <div class="form-group" style="text-align: center;">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
