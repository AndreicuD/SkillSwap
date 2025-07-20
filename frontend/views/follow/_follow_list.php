<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;

use common\models\User;
$this->registerJs(<<<JS
    (function () {
        $(document).off('click', '.follow-toggle-btn');

        $(document).on('click', '.follow-toggle-btn', function(e) {
            e.preventDefault();
            var btn = $(this);
            var userId = btn.data('user-id');
            var url = btn.data('url');

            $.post(url, {
                _csrf: yii.getCsrfToken()
            }, function (data) {
                console.log('Response:', data);
                if (data.success) {
                    var countElem = document.getElementById('following-count');
                    if (data.following) {
                        if (countElem) countElem.innerText = parseInt(countElem.innerText) + 1;
                        btn.text('Unfollow')
                            .removeClass('btn-secondary')
                            .addClass('btn-outline-secondary');
                    } else {
                        if (countElem) countElem.innerText = parseInt(countElem.innerText) - 1;
                        btn.text('Follow')
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
    })();
JS);
?>
<?php foreach ($users as $u): ?>
    <?php 
        $src = $u->checkFileExists() ? $u ->getSrc() : '/img/default_avatar.png'; 
        $isFollowing = Yii::$app->user->identity->isFollowing($u->id);
    ?>
    <div class="group_together mb-2">
        <a href="<?= Url::to(['/user/index', 'public_id' => $u->public_id]) ?>" class="d-flex align-items-center mb-2">
            <img src="<?= $src ?>" width="32" height="32" class="rounded-circle me-2">
            <span><?= Html::encode(User::getUsername($u->id)) ?></span>
        </a>
        <!-- follow button -->
        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->id !== $u->id): ?>
            <?= Html::button(
                $isFollowing ? 'Unfollow' : 'Follow',
                [
                    'class' => 'btn follow-toggle-btn ' . ($isFollowing ? 'btn-outline-secondary' : 'btn-secondary'),
                    'data-user-id' => $u->id,
                    'data-url' => Url::to(['/follow/toggle', 'id' => $u->id]),
                ]
            ) ?>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
<?php if (empty($users)): ?>
    <p class="text-muted text-center"><?= Yii::t('app', 'No results found') ?>.</p>
<?php endif; ?>