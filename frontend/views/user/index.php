<?php
/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\Pjax;
use common\models\User;

$src = $user->checkFileExists() ? $user ->getSrc() : '/img/default_avatar.png';

$this->title = User::getUsername(Yii::$app->user->identity->id) . ' Profile';
$this->registerJs(
    new JsExpression(
        <<<JS
        $('.ajax-link').on('click', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            $.get(url, function(data) {
                $('#ajax-container').html(data);
            });
        });
JS
    )
);
?>

<div class="container-fluid d-flex">
    <div class="p-3 border-end" style="min-width: 280px; max-width: 300px;">
        <div class="text-center">
            <div class="text-center">
                <img class='avatar' src="<?=$src?>" alt="<?= Yii::t('app', 'User Avatar') ?>" width="250" height="250">
            </div>
            <h5 class="mt-2 mb-1"><?= Html::encode(User::getUsername(Yii::$app->user->identity->id)) ?></h5>  
        </div>

        <div class="mb-2">
            <p class="form-control bg-light" readonly style="min-height: 80px;"> 
                <?= Html::encode($user->description ?: 'No description.') ?>
            </p>
        </div>

        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->id !== $user->id): ?>
            <?= Html::a(Yii::t('app', 'Follow'), ['profile/follow', 'id' => $user->id], [
                'class' => 'btn btn-outline-primary btn-sm w-100 mb-3 ajax-link',
                'data-method' => 'post'
            ]) ?>
        <?php endif; ?>

        <div class="list-group">
            <?= Html::a(Yii::t('app', 'Articole'), ['profile/articles', 'id' => $user->id], [
                'class' => 'list-group-item list-group-item-action ajax-link'
            ]) ?>
            <?= Html::a(Yii::t('app', 'Cursuri'), ['profile/courses', 'id' => $user->id], [
                'class' => 'list-group-item list-group-item-action ajax-link'
            ]) ?>
            <?= Html::a(Yii::t('app', 'Stats'), ['profile/stats', 'id' => $user->id], [
                'class' => 'list-group-item list-group-item-action ajax-link'
            ]) ?>
        </div>

        <hr>
        <?php if (Yii::$app->user->id === $user->id): ?>
            <?= Html::a(Yii::t('app', 'Settings'), ['/user/settings'], [
                'class' => 'd-block mb-2 text-muted'
            ]) ?>
            <?= Html::a(Yii::t('app', 'Logout'), ['/site/logout'], [
                'data-method' => 'post',
                'class' => 'd-block text-danger'
            ]) ?>
        <?php endif; ?>
    </div>

    <div id="ajax-container" class="flex-fill p-4">
        <div class="text-center text-muted">
            <?= Yii::t('app', 'Select a section to view.') ?>
        </div>
    </div>
</div>
