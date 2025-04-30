<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\Category;
use common\models\User;
use common\models\Transaction;

$this->title = $model->title;
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    
    <div class="article-content user-select-none">
        <div class="article-image">
            <img src="../frontend/web/img/placeholder.jpg" alt="HTML5 Icon">
            <div class="article-title">
                <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
                <h4 class="text-center"><?= Html::encode(User::getUsername($model->user_id)) ?></h4>
            </div>
        </div>
        <br>
        <?php 
            if(Transaction::findTransaction(Yii::$app->user->id, $model->id) || $model->user_id == Yii::$app->user->id) {
                echo $model->content;
            } else {
                echo '<div class="text-center lead">';
                echo Yii::t('app', 'You can not acces this article. Check if you have bought it or if you are logged in.');
                echo '</div>';
            }
        ?>
    </div>
</div>