<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\Category;
use common\models\User;
use common\models\Transaction;
use common\models\Rating;
use kartik\widgets\ActiveForm;
use kartik\widgets\StarRating;

$reviewModel->article_id = $model->id;

$this->title = $model->title;
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    
    <div class="user-select-none">
        <div class="article-title-sm">
            <p class="text-center article-title"><?= Html::encode($this->title) ?></p>
            <p class="text-center article-user">- <?= Html::encode(User::getUsername($model->user_id)) ?> -</p>
        </div>
        <div class="article-image">
            <img src="../frontend/web/img/placeholder.jpg" alt="HTML5 Icon">
            <div class="article-title-area">
                <p class="text-center article-title"><?= Html::encode($this->title) ?></p>
                <p class="text-center article-user">- <?= Html::encode(User::getUsername($model->user_id)) ?> -</p>
            </div>
        </div>
        <br>
        <div class="article-content">
            <?php 
                if(Transaction::findTransaction(Yii::$app->user->id, $model->id) || $model->user_id == Yii::$app->user->id) {
                    echo $model->content;
                } else {
                    echo '<div class="text-center lead">';
                    echo Yii::t('app', 'You can not acces this article. Check if you have bought it or if you are logged in.');
                    echo '</div>';
                }
            ?>

            <hr>
            <div class="w-100">
                <h2><?= Yii::t('app', 'Leave a review!'); ?></h2>
                <?php $form = ActiveForm::begin([
                    'id' => 'article-form',
                    'type' => ActiveForm::TYPE_FLOATING,
                    'action' => ['review/create', 'public_id' => $model->public_id], // Specify the route to the create action
                    'method' => 'post',
                ]); ?>
            
                <?= $form->errorSummary($reviewModel);?>
        
                <?= $form->field($reviewModel, 'title')->label(Yii::t('app', 'Title')) ?>
                <?= $form->field($reviewModel, 'body')->textarea(['rows' => 4, 'style' => 'min-height: 160px']) ?>
            
                <?= $form->field($reviewModel, 'value')->widget(StarRating::classname(), [
                    'pluginOptions' => ['step' => 0.5]
                ])->label(false);?>
        
                <?= Html::activeHiddenInput($reviewModel, 'article_id'); ?>
        
                <div class="w-100 text-center">
                    <?= Html::button(Yii::t('app', 'Save Review'),['class' => ['btn btn-primary rotate_on_hover scale_on_hover mb-3'], 'type' => 'submit']) ?>
                </div>
            
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        
    </div>
</div>