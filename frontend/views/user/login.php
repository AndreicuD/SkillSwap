<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \backend\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = Yii::t('app', 'Login');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1 class="text-center page_title"><?= Html::encode($this->title) ?></h1>

    <p class="text-center"><?= Yii::t('app', 'Please fill out the following fields to login:') ?></p>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <?php $form = ActiveForm::begin(['id' => 'login-form', 'layout' => 'floating']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="my-1 mx-0" style="color:#999;">
                    <?= Yii::t('app', 'If you forgot your password you can ') ?> <?= Html::a('reset it', ['user/request-password-reset']) ?>.
                    <!--<br>
                    Need new verification email? <?= Html::a('Resend', ['user/resend-verification-email']) ?>-->
                </div>
                <br>
                <div class="form-group" style="text-align: center;">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
