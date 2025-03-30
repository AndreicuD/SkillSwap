<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\PasswordResetRequestForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Request password reset';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">
    <h1 class="text-center page_title"><?= Html::encode($this->title) ?></h1>

    <p class="text-center">Please fill out your email. A link to reset password will be sent there.</p>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form', 'layout' => 'floating']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
                <br>
                <div class="form-group">
                    <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
