<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\FileInput;

$this->title = 'Settings';

$src = $userModel->checkFileExists() ? $userModel ->getSrc() : '/img/default_avatar.png';
?>

<div class="site-index">
    <h1 style="text-align: center;" class="page_title"><?= Html::encode($this->title) ?></h1>

    <div class="padd-25">
        <div>
            <?php $form = ActiveForm::begin([
                'id' => 'form-change-info',
                'type' => ActiveForm::TYPE_VERTICAL,
                'action' => ['user/settings'], // Specify the route to the create action
                'method' => 'post',
            ]); ?>
    
            <?= $form->errorSummary($userModel);?>

            <div class="text-center">
                <img class='avatar' src="<?=$src?>" alt="<?= Yii::t('app', 'User Avatar') ?>" width="250" height="250">
            </div>
            
            <div class="group_together">
                <div style="width: 80%">
                    <?= $form->field($userModel, 'avatar')->widget(FileInput::classname(), [
                        'name' => 'avatar',
                        'pluginOptions' => [
                            'showPreview' => false,
                            'showCaption' => true,
                            'showRemove' => false,
                            'showUpload' => false,
                            'browseClass' => 'btn btn-primary btn-block',
                            'browseIcon' => '<i class="fas fa-camera"></i> ',
                            'browseLabel' =>  'Select Photo'
                        ],
                        'options' => ['accept' => 'image/*']
                    ]); ?>
                </div>
                <div class="delete_avatar_button">
                    <?= Html::a(Yii::t('app', 'Delete Avatar'),
                    ['user/delete-avatar'],
                    [
                        'class' => ['btn btn-outline-danger rotate_on_hover mb-3'], 
                    ]) ?>
                </div>
            </div>
            <p class="small gray"><?= Yii::t("app", "If you can't see any changes try clearing your cache, or wait and it will eventualy change!") ?></p>
            
            <?= $form->field($userModel, 'firstname')->label(Yii::t('app', 'First Name')) ?>
            <?= $form->field($userModel, 'lastname')->label(Yii::t('app', 'Last Name')) ?>
            <?= $form->field($userModel, 'email')->label(Yii::t('app', 'Email')) ?>
    
            <div class="row">
                <div class="col">
                    <input type="submit" value="<?= Yii::t('app', 'Save Changes') ?>" class="btn btn-primary">
                </div>
                <div class="col">
                    <input type="reset" value="<?= Yii::t('app', 'Reset') ?>" class="btn btn-warning">
                </div>
            </div>
            <?php ActiveForm::end(); ?>

            <hr>

            <h4><?= Yii::t('app', 'Change Password') ?></h4>
            <!-- CHANGE PASSWORD FORM -->
            <?php $passwordForm = ActiveForm::begin([
                'id' => 'form-change-password',
                'type' => ActiveForm::TYPE_VERTICAL,
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