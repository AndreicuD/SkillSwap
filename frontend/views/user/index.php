<?php
/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\Pjax;
use common\models\User;

$src = $user->checkFileExists() ? $user ->getSrc() : '/img/default_avatar.png';

$this->title = User::getUsername($user->id) . ' Profile';
$this->registerJs(
    new JsExpression(
        <<<JS
        function loadAjaxContent(link) {
            $.get(link, function(data) {
                $('#ajax-container').html(data);
            });
        }

        $(document).on('click', '.ajax-link', function(e) {
            e.preventDefault();
            loadAjaxContent($(this).attr('href'));
        });
JS
    )
);
?>
<div class="container-fluid d-flex group_together">
    <div class="p-3" style="min-width: 280px; max-width: 300px;">
        <div class="text-center">
            <div class="text-center">
                <img class='avatar' src="<?=$src?>" alt="<?= Yii::t('app', 'User Avatar') ?>" width="250" height="250">
            </div>
            <h3 class="mt-2 mb-1"><?= Html::encode(User::getUsername($user->id)) ?></h3>  
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
        <div class="group_together text-center gray">
            <div style="width: 49%">
                <p>Followers: 4</p>
            </div>
            <div class="vr phone-disappear" style="margin-bottom: 16px;"></div>
            <div style="width: 49%">
                <p>Following: 1</p>
            </div>
        </div>
        
        <div class="list-group">
            <?= Html::a(Yii::t('app', 'Articles'), ['profile/articles', 'id' => $user->id], [
                'class' => 'list-group-item list-group-item-action ajax-link'
            ]) ?>
            <?= Html::a(Yii::t('app', 'Courses'), ['profile/courses', 'id' => $user->id], [
                'class' => 'list-group-item list-group-item-action ajax-link'
            ]) ?>
            <?php if (Yii::$app->user->id == $user->id): ?>
                <?= Html::a(Yii::t('app', 'Stats'), ['profile/stats', 'id' => $user->id], [
                    'class' => 'list-group-item list-group-item-action ajax-link'
                ]) ?>
            <?php endif; ?>
        </div>

        <?php if (Yii::$app->user->id === $user->id): ?>
            <hr>
            <div class="group_together">
                <?= Html::a(Yii::t('app', 'Settings'), ['/user/settings'], [
                    'class' => 'btn btn-primary scale_on_hover rotate_on_hover'
                ]) ?>
                <?= Html::a(Yii::t('app', 'Logout'), ['/user/logout'], [
                    'data-method' => 'post',
                    'class' => 'btn btn-danger rotate_on_hover'
                ]) ?>
            </div>
        <?php endif; ?>
    </div>

    <div id="ajax-container" class="flex-fill p-4">
        <?php if (Yii::$app->user->id === $user->id): ?>
            <div>
                <h1><?= Yii::t('app', 'Welcome') ?>, <?= Html::encode($user->firstname) ?>!🤘</h1>
                <hr>
            </div>
        <?php endif; ?>
        <divclass="text-muted">
            <?= Yii::t('app', 'Select a section to view.') ?>
        </div>
    </div>
</div>
