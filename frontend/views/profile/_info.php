<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \common\models\User $user */
$point_svg = '<svg  xmlns="http://www.w3.org/2000/svg"  width="1em"  height="1em"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-analyze mb-3"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -6.986 -6.918a8.095 8.095 0 0 0 -8.019 3.918" /><path d="M4 13a8.1 8.1 0 0 0 15 3" /><path d="M19 16m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M5 8m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /></svg>';
?>
<div class="welcome-text">
    <h1><?= Yii::t('app', 'Welcome') ?>, <?= Html::encode($user->firstname) ?>!🤘</h1>
</div>
<br>
<!-- Bonus Info Section -->
<div class="group_together text-center">
    <div class="w-50">
        <h4><?= Yii::t('app', 'Next Bonus In:') ?></h4>
        <p class="bonus-timer">
            <span id="bonus-timer" class="text-primary fw-bold"></span>
        </p>
    </div>
    <div class="w-50">
        <h4><?= Yii::t('app', 'Bonus Streak') ?></h4>
        <p class="bonus-timer">
            <span class="text-primary fw-bold"><?= Html::encode($user->bonus_streak ?? 0) ?> <?= ($user->bonus_streak == 1) ? 'Day' : 'Days' ?></span>
        </p>
    </div>
</div>
<div class="w-100 flex-row-even">
    <a href="<?= Url::to('user/articles') ?>" class="w-50 card add-element-card scale_on_hover rotate_on_hover" style="text-decoration: none;">
        <div class="card-icon" style="margin-bottom: 12px; color: #555;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-news"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16 6h3a1 1 0 0 1 1 1v11a2 2 0 0 1 -4 0v-13a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1v12a3 3 0 0 0 3 3h11" /><path d="M8 8l4 0" /><path d="M8 12l4 0" /><path d="M8 16l4 0" /></svg>
        </div>
        <div class="card-title" style="font-weight: bold; font-size: 16px; color: #444;">
            <?= Yii::t('app','See All Your Articles') ?>
        </div>
    </a>
    <a href="<?= Url::to('user/courses') ?>" class="w-50 card add-element-card scale_on_hover rotate_on_hover" style="text-decoration: none;">
        <div class="card-icon" style="margin-bottom: 12px; color: #555;">
            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-map-route"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7l6 -3l6 3l6 -3v13l-6 3l-6 -3l-6 3v-13" /><path d="M9 12v.01" /><path d="M6 13v.01" /><path d="M17 15l-4 -4" /><path d="M13 15l4 -4" /></svg>
        </div>
        <div class="card-title" style="font-weight: bold; font-size: 16px; color: #444;">
            <?= Yii::t('app','See All Your Courses') ?>
        </div>
    </a>
</div> 
<hr>

<h4 class="text-center"><?= Yii::t('app', 'Bought')?>:</h4>
<div class="w-100 flex-row-even">
    <a href="<?= Url::to('user/articles-bought') ?>" class="w-50 card add-element-card scale_on_hover rotate_on_hover" style="text-decoration: none;">
        <div class="card-icon" style="margin-bottom: 12px; color: #555;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-news"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16 6h3a1 1 0 0 1 1 1v11a2 2 0 0 1 -4 0v-13a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1v12a3 3 0 0 0 3 3h11" /><path d="M8 8l4 0" /><path d="M8 12l4 0" /><path d="M8 16l4 0" /></svg>
        </div>
        <div class="card-title" style="font-weight: bold; font-size: 16px; color: #444;">
            <?= Yii::t('app','See Bought Articles') ?>
        </div>
    </a>
    <a href="<?= Url::to('user/courses-bought') ?>" class="w-50 card add-element-card scale_on_hover rotate_on_hover" style="text-decoration: none;">
        <div class="card-icon" style="margin-bottom: 12px; color: #555;">
            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-map-route"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7l6 -3l6 3l6 -3v13l-6 3l-6 -3l-6 3v-13" /><path d="M9 12v.01" /><path d="M6 13v.01" /><path d="M17 15l-4 -4" /><path d="M13 15l4 -4" /></svg>
        </div>
        <div class="card-title" style="font-weight: bold; font-size: 16px; color: #444;">
            <?= Yii::t('app','See Bought Courses') ?>
        </div>
    </a>
</div>

<div class="gray text-center">
    <?= Yii::t('app', 'Select a section to view.') ?>
</div>

<?php
$remainingSeconds = $bonusData['remaining_seconds'];
$this->registerJs(<<<JS
    let remaining = $remainingSeconds;

    function updateTimer() {
        if (remaining <= 0) {
            document.getElementById('bonus-timer').textContent = '✅ Available!';
            return;
        }

        let hours = Math.floor(remaining / 3600);
        let minutes = Math.floor((remaining % 3600) / 60);
        let seconds = remaining % 60;

        document.getElementById('bonus-timer').textContent =
            hours.toString().padStart(2, '0') + ':' +
            minutes.toString().padStart(2, '0') + ':' +
            seconds.toString().padStart(2, '0');

        remaining--;
    }

    updateTimer();
    setInterval(updateTimer, 1000);
JS); 
?>