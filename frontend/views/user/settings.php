<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\FileInput;

$this->title = 'Settings';
?>

<div class="site-index">
    <h1 style="text-align: center;" class="page_title"><?= Html::encode($this->title) ?></h1>

    <div class="text-center" style="padding: 0 25%">
        <div>
            <?php $form = ActiveForm::begin([
                'id' => 'form-change-info',
                'type' => ActiveForm::TYPE_FLOATING,
                'action' => ['user/settings'], // Specify the route to the create action
                'method' => 'post',
            ]); ?>
    
            <?= $form->errorSummary($userModel);?>
            
            <?= $form->field($userModel, 'firstname')->label(Yii::t('app', 'First Name')) ?>
            <?= $form->field($userModel, 'lastname')->label(Yii::t('app', 'Last Name')) ?>
            <?= $form->field($userModel, 'email')->label(Yii::t('app', 'Email')) ?>
    
            <div class="row">
                <div class="col">
                    <input type="reset" value="<?= Yii::t('app', 'Reset') ?>" class="btn btn-warning">
                </div>
                <div class="col">
                    <input type="submit" value="<?= Yii::t('app', 'Save Changes') ?>" class="btn btn-primary">
                </div>
            </div>
            <?php ActiveForm::end(); ?>

            <hr>

            <h4><?= Yii::t('app', 'Change Password') ?></h4>
            <!-- CHANGE PASSWORD FORM -->
            <?php $passwordForm = ActiveForm::begin([
                'id' => 'form-change-password',
                'type' => ActiveForm::TYPE_FLOATING,
                'action' => ['user/change-password'], 
                'method' => 'post',
            ]); ?>

            <?= $passwordForm->errorSummary($changePasswordModel); ?>

            <?= $passwordForm->field($changePasswordModel, 'current_password')->passwordInput()->label('Current Password') ?>
            <?= $passwordForm->field($changePasswordModel, 'new_password')->passwordInput()->label('New Password') ?>
            <?= $passwordForm->field($changePasswordModel, 'confirm_password')->passwordInput()->label('Confirm New Password') ?>
            <p class="small_gray_text"><?= Yii::t('app', 'Forgot you current password? Change it ') ?><?=Html::a(Yii::t('app', 'here'), Url::to(['user/request-password-reset'])); ?>.</p>

            <input type="submit" value="<?= Yii::t('app', 'Change Password') ?>" class="btn btn-primary">
            <?php ActiveForm::end(); ?>
        </div> 
    </div>
</div>