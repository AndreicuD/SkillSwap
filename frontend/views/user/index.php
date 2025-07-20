<?php
/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\Pjax;
use common\models\User;
use common\models\Follow;

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

$isFollowing = !Yii::$app->user->isGuest && Follow::find()
    ->where(['from_user_id' => Yii::$app->user->id, 'to_user_id' => $user->id])
    ->exists();

$followerCount = $user->getFollowers()->count();
$followingCount = $user->getFollowing()->count();

$this->registerJs(<<<JS
$(document).on('click', '.follow-toggle-btn', function () {
    const \$btn = $(this);
    const userId = \$btn.data('user-id');

    if (!userId) {
        alert('No user ID found.');
        return;
    }

    $.post('/follow/toggle?id=' + userId, {
        _csrf: yii.getCsrfToken()
    }, function (data) {
        console.log('Response:', data);
        if (data.success) {
            if (data.following) {
                document.getElementById('follower-count').innerText++;
                \$btn.text('Unfollow')
                    .removeClass('btn-secondary')
                    .addClass('btn-outline-secondary');
            } else {
                document.getElementById('follower-count').innerText--;
                \$btn.text('Follow')
                    .removeClass('btn-outline-secondary')
                    .addClass('btn-secondary');
            }
        } else {
            alert('Server error: ' + (data.message || 'unknown'));
        }
    }).fail(function () {
        alert('AJAX request failed.');
    });
});
JS);


$js = <<<JS
$('.open-follow-modal').on('click', function(e) {
    e.preventDefault();

    const userId = $(this).data('user-id');
    const type = $(this).data('type');
    const modal = $('#followModal');
    const modalBody = $('#followModalBody');
    const modalTitle = $('#followModalLabel');

    modalTitle.text(type === 'followers' ? 'Followers' : 'Following');
    modalBody.html('<p class="text-center">Loading...</p>');
    modal.modal('show');

    $.ajax({
        url: '/follow/' + type,
        data: { id: userId },
        success: function(response) {
            modalBody.html(response);
        },
        error: function() {
            modalBody.html('<p class="text-danger text-center">Failed to load content.</p>');
        }
    });
});
JS;

$this->registerJs($js);

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
            <div id="follow-btn-container" style="padding-bottom: 16px;">
                <?= Html::button(
                    $isFollowing ? 'Unfollow' : 'Follow',
                    [
                        'class' => 'btn w-100 follow-toggle-btn ' . ($isFollowing ? 'btn-outline-secondary' : 'btn-secondary'),
                        'data-user-id' => $user->id,
                    ]
                ) ?>
            </div>
        <?php endif; ?>

        <div class="group_together text-center gray">
            <div style="width: 49%">
                <p>
                    <a href="#" class="open-follow-modal text-decoration-none" style="color: inherit;" data-type="followers" data-user-id="<?= $user->id ?>">
                        <?= Yii::t('app', 'Followers') ?>
                        <span class="badge bg-secondary" id="follower-count"><?= $followerCount ?></span>
                    </a>
                </p>
            </div>
            <div class="vr phone-disappear" style="margin-bottom: 16px;"></div>
            <div style="width: 49%">
                <p>
                    <a href="#" class="open-follow-modal text-decoration-none" style="color: inherit;" data-type="following" data-user-id="<?= $user->id ?>">
                        <?= Yii::t('app', 'Following') ?>
                        <span class="badge bg-secondary" id="following-count"><?= $followingCount ?></span>
                    </a>
                </p>
            </div>
        </div>
        
        
        <div class="list-group">
            <?php if (Yii::$app->user->id == $user->id): ?>
                <?= Html::a(Yii::t('app', 'Quick Info'), ['profile/info', 'id' => $user->id], [
                    'class' => 'list-group-item list-group-item-action ajax-link'
                ]) ?>
            <?php endif; ?>
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
                    'class' => 'btn btn-danger scale_on_hover rotate_on_hover'
                ]) ?>
            </div>
        <?php endif; ?>
    </div>

    <div id="ajax-container" class="flex-fill p-4">
        <?php if (Yii::$app->user->id == $user->id): ?>
            <div id="bonus-section"></div>
            <?php
            $bonusUrl = Url::to(['profile/info', 'id' => $user->id]);
            $this->registerJs(<<<JS
                $.get('$bonusUrl', function(data) {
                    $('#bonus-section').html(data);
                });
            JS);
            ?>
        <?php else: ?>
            <div id="article-section"></div>
            <?php
            $articlesUrl = Url::to(['profile/articles', 'id' => $user->id]);
            $this->registerJs(<<<JS
                $.get('$articlesUrl', function(data) {
                    $('#article-section').html(data);
                });
            JS);
            ?>
        <?php endif; ?>
    </div>
</div>

<!-- Follow Modal -->
<div class="modal fade" id="followModal" tabindex="-1" role="dialog" aria-labelledby="followModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="followModalLabel"><?= Yii::t('app', 'Loading...') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="followModalBody">
        <p class="text-center"><?= Yii::t('app', 'Loading...') ?></p>
      </div>
    </div>
  </div>
</div>