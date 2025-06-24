<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use common\models\User;

AppAsset::register($this);
$point_svg = '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-analyze"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -6.986 -6.918a8.095 8.095 0 0 0 -8.019 3.918" /><path d="M4 13a8.1 8.1 0 0 0 15 3" /><path d="M19 16m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M5 8m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /></svg>';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Antonio:wght@100..700&family=Funnel+Sans:ital,wght@0,300..800;1,300..800&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
</head>


<body class="d-flex flex-column" data-bs-theme="light">
<?php $this->beginBody() ?>

<?php
        NavBar::begin([
            //'brandImage' => '/img/logo-dark.png',
            //'brandLabel' => Yii::$app->name,
            //'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'id' => 'navbar',
                'class' => 'navbar bg-primary navbar-expand-md sticky-top',

            ],
        ]);
    ?>

    <!-- keep this if you also want a logo by the navbar label -->

    <svg id="logo-nav" data-name="logo-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 493.07 494.88"><defs><style>.cls-1{fill:#fff;stroke:#63b339;stroke-miterlimit:10;stroke-width:5px;}</style></defs><path class="cls-1" d="M68.79,352,7,290.17,146.41,150.76a34.45,34.45,0,0,0,10.06-23.28l.22-7a19.59,19.59,0,0,1,5.75-13.28L234.65,35A15.41,15.41,0,0,1,251.3,31.6l.15.06a16.09,16.09,0,0,1,9.35,10.17h0c1.68,5.42-1.94,9.3-5.92,13.34l-31,31.33c-1.2,1.22.83,5.52,2,6.73h0a3.11,3.11,0,0,0,4.41,0L275,48.52a9.43,9.43,0,0,1,8.27-2.63h0a10.61,10.61,0,0,1,8.62,8.38l.21,1a9.45,9.45,0,0,1-2.62,8.57L243.3,109.53a3.1,3.1,0,0,0,.09,4.51l2.42,2.23a3.12,3.12,0,0,0,4.3-.08l48.26-47.74a11.33,11.33,0,0,1,10.42-3l.21,0a8.71,8.71,0,0,1,6.75,7l0,.18A11.67,11.67,0,0,1,312.43,83l-46.66,45.63a3.12,3.12,0,0,0,0,4.44l.61.61a3.11,3.11,0,0,0,4.34.06l42.79-40.3a12.21,12.21,0,0,1,12.35-2.65l1.53.54a11,11,0,0,1,7.14,8.07h0a19.47,19.47,0,0,1-5.27,17.84l-66.6,66.6,19.89,20.47L269,211.14a27.9,27.9,0,0,1-11,2.91l-21,1.12a29.31,29.31,0,0,1-11.66-1.73L221,211.86a12.45,12.45,0,0,0-13,2.84Z" transform="translate(-3.47 -2.56)"/><path class="cls-1" d="M351.83,432.12,290,493.9,150.63,354.5a34.37,34.37,0,0,0-23.27-10.07l-7-.22a19.62,19.62,0,0,1-13.29-5.74L34.87,266.26a15.4,15.4,0,0,1-3.39-16.65l.06-.15a16.08,16.08,0,0,1,10.17-9.36h0C47.13,238.43,51,242,55,246l31.34,31c1.22,1.2,5.51-.83,6.72-2h0a3.11,3.11,0,0,0,0-4.41L48.4,225.9a9.45,9.45,0,0,1-2.64-8.27h0A10.62,10.62,0,0,1,54.15,209l1-.2a9.44,9.44,0,0,1,8.57,2.62l45.66,46.17a3.11,3.11,0,0,0,4.51-.08l2.22-2.43a3.11,3.11,0,0,0-.08-4.3L68.32,202.54a11.36,11.36,0,0,1-3-10.43l0-.2a8.71,8.71,0,0,1,7-6.76l.18,0a11.72,11.72,0,0,1,10.34,3.35l45.63,46.66a3.11,3.11,0,0,0,4.43,0l.61-.61a3.12,3.12,0,0,0,.07-4.34L93.34,187.41a12.17,12.17,0,0,1-2.64-12.34l.53-1.54a11,11,0,0,1,8.07-7.14h0a19.48,19.48,0,0,1,17.85,5.27l66.59,66.6,20.48-19.89L211,231.91a27.61,27.61,0,0,1,2.91,11l1.13,21a29.67,29.67,0,0,1-1.73,11.66l-1.59,4.37a12.45,12.45,0,0,0,2.84,13Z" transform="translate(-3.47 -2.56)"/><path class="cls-1" d="M431.21,148.24,493,210,353.59,349.44a34.43,34.43,0,0,0-10.06,23.28l-.22,7A19.6,19.6,0,0,1,337.56,393l-72.21,72.22a15.41,15.41,0,0,1-16.65,3.39l-.15-.06a16.11,16.11,0,0,1-9.35-10.18h0c-1.68-5.41,1.94-9.29,5.92-13.33l31-31.33c1.2-1.22-.83-5.52-2-6.73h0a3.11,3.11,0,0,0-4.41,0L225,451.68a9.46,9.46,0,0,1-8.27,2.63h0a10.61,10.61,0,0,1-8.62-8.38l-.21-1a9.44,9.44,0,0,1,2.62-8.56l46.18-45.66a3.12,3.12,0,0,0-.09-4.52l-2.42-2.22a3.12,3.12,0,0,0-4.3.08l-48.26,47.74a11.33,11.33,0,0,1-10.42,3l-.21,0a8.71,8.71,0,0,1-6.75-7.05l0-.18a11.66,11.66,0,0,1,3.35-10.33l46.66-45.63a3.12,3.12,0,0,0,0-4.44l-.61-.61a3.11,3.11,0,0,0-4.34-.06l-42.79,40.3a12.19,12.19,0,0,1-12.35,2.64l-1.53-.53a11,11,0,0,1-7.14-8.07h0a19.47,19.47,0,0,1,5.27-17.84l66.6-66.6-19.89-20.47,13.54-6.8a27.9,27.9,0,0,1,11-2.91L263,285a29.31,29.31,0,0,1,11.66,1.73l4.36,1.58a12.47,12.47,0,0,0,13-2.84Z" transform="translate(-3.47 -2.56)"/><path class="cls-1" d="M148,67.88,209.8,6.1,349.21,145.5a34.41,34.41,0,0,0,23.28,10.07l7,.22a19.57,19.57,0,0,1,13.28,5.74L465,233.74a15.4,15.4,0,0,1,3.39,16.65l-.06.15a16.11,16.11,0,0,1-10.17,9.36h0c-5.42,1.67-9.3-1.94-13.34-5.92l-31.33-31c-1.22-1.2-5.52.83-6.73,2h0a3.11,3.11,0,0,0,0,4.41l44.71,44.71a9.42,9.42,0,0,1,2.63,8.27h0A10.61,10.61,0,0,1,445.7,291l-1,.2a9.42,9.42,0,0,1-8.57-2.62L390.44,242.4a3.11,3.11,0,0,0-4.51.08l-2.23,2.43a3.12,3.12,0,0,0,.08,4.3l47.74,48.25a11.39,11.39,0,0,1,3,10.43l-.05.2a8.72,8.72,0,0,1-7.05,6.76l-.18,0a11.71,11.71,0,0,1-10.33-3.35l-45.63-46.66a3.11,3.11,0,0,0-4.43,0l-.61.61a3.1,3.1,0,0,0-.07,4.34l40.3,42.79a12.19,12.19,0,0,1,2.65,12.34l-.54,1.54a11,11,0,0,1-8.07,7.14h0a19.47,19.47,0,0,1-17.84-5.27l-66.6-66.6-20.47,19.89-6.8-13.54a28,28,0,0,1-2.91-11l-1.12-21a29.49,29.49,0,0,1,1.73-11.66l1.58-4.37a12.43,12.43,0,0,0-2.84-13Z" transform="translate(-3.47 -2.56)"/><path class="cls-1" d="M146.41,150.76a34.45,34.45,0,0,0,10.06-23.28l.22-7a19.59,19.59,0,0,1,5.75-13.28L234.65,35A15.41,15.41,0,0,1,251.3,31.6l.15.06a16.09,16.09,0,0,1,9.35,10.17h0c1.68,5.42-1.94,9.3-5.92,13.34l-31,31.33c-1.2,1.22.83,5.52,2,6.73h0a3.11,3.11,0,0,0,4.41,0L275,48.52a9.43,9.43,0,0,1,8.27-2.63h0a10.61,10.61,0,0,1,8.62,8.38l.21,1a9.45,9.45,0,0,1-2.62,8.57L243.3,109.53a3.1,3.1,0,0,0,.09,4.51l2.42,2.23a3.12,3.12,0,0,0,4.3-.08l48.26-47.74a11.33,11.33,0,0,1,10.42-3l.21,0a8.71,8.71,0,0,1,6.75,7l0,.18A11.67,11.67,0,0,1,312.43,83l-46.66,45.63a3.12,3.12,0,0,0,0,4.44l.61.61a3.11,3.11,0,0,0,4.34.06l42.79-40.3a12.21,12.21,0,0,1,12.35-2.65l1.53.54a11,11,0,0,1,7.14,8.07h0a19.47,19.47,0,0,1-5.27,17.84l-66.6,66.6,19.89,20.47L269,211.14a27.9,27.9,0,0,1-11,2.91l-21,1.12a29.31,29.31,0,0,1-11.66-1.73L221,211.86a12.45,12.45,0,0,0-13,2.84" transform="translate(-3.47 -2.56)"/></svg>
    <a class="navbar-brand" href="/site/index"><?= Yii::$app->name ?></a>

    <?php
        if (Yii::$app->user->isGuest) {
            $menuItems = [
                ['label' => 'Home', 'url' => ['/site/index']],
                ['label' => 'Contact', 'url' => ['/site/contact']],
                ['label' => 'FAQ', 'url' => ['/site/faq']],
            ];
        } else {
            $menuItems = [
                ['label' => 'Home', 'url' => ['/site/index']],
                ['label' => 'Contact', 'url' => ['/site/contact']],
                ['label' => 'Articles', 'url' => ['/article/index']],
                ['label' => 'Courses', 'url' => ['/course/index']],
                ['label' => 'FAQ', 'url' => ['/site/faq']],
            ];
        }

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav ms-auto mb-2 mb-md-0'],
            'items' => $menuItems,
        ]);
        if (Yii::$app->user->isGuest) {
            echo "<div class='btn-group login_logoutbutton'>";
            
            echo Html::a('Signup',['/user/signup'],['class' => ['btn btn-primary signup text-decoration-none']]);
            echo Html::a('Login',['/user/login'],['class' => ['btn btn-primary login text-decoration-none']]);

            echo "</div>";

        } else {
            echo '<div class="btn-group login_logoutbutton">';
                echo '<a class="btn btn-primary dropdown-toggle rotate_on_hover" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">';
                    echo User::getUsername(Yii::$app->user->identity->id) . " - ";
                    echo Yii::$app->user->identity->points . $point_svg;
                echo '</a>';
                echo '<ul class="dropdown-menu dropdown-menu-lg-end">';
                    echo Html::tag('li',Html::a('My articles',['/user/articles'],['class' => ['dropdown-item']]));

                    echo Html::tag('li',Html::a('My courses',['/user/courses'],['class' => ['dropdown-item']]));
                    
                    echo Html::tag('li',Html::a('My Bookmarks',['/bookmark/index'],['class' => ['dropdown-item']]));

                    echo Html::tag('li',Html::tag('hr', '', ['class' => 'dropdown-divider']));

                    echo Html::tag('li',Html::a('Settings',['/user/settings'],['class' => ['dropdown-item']]));
                    
                    echo Html::tag('li',Html::a('Logout',['/user/logout'],['class' => ['btn btn-link logout text-decoration-none']]),['class' => ['d-flex login_logoutbutton']]);
                echo '</ul>';
            echo '</div>';
            //echo Html::tag('div',Html::a('Logout (' . Yii::$app->user->identity->username . ')',['/user/logout'],['class' => ['btn btn-link logout text-decoration-none']]),['class' => ['d-flex login_logoutbutton']]);
        }
        NavBar::end();
?>

<main role="main" class="flex-shrink-0">
    <div class="<?= Yii::$app->requestedRoute == 'site/index' || Yii::$app->requestedRoute == 'article/read' ? '' : 'container' ?>">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?php if (Yii::$app->session->hasFlash('dailyBonus')): ?>
            <?php $bonus = Yii::$app->session->getFlash('dailyBonus'); ?>
            <div class="alert alert-success daily_alert">
                🎁 <?= Yii::t('app', 'You earned') ?> <strong><?= $bonus['points'] ?> <?= Yii::t('app', 'points') ?></strong> <?= Yii::t('app', 'today') ?>!
                <br>
                🔥 <?= Yii::t('app', 'Current streak') ?>: <strong><?= $bonus['streak'] ?> <?= Yii::t('app', 'days') ?></strong>
            </div>
        <?php endif; ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer footer-light mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?> - <?= Html::a(Yii::t('app', 'Terms and Conditions'),['/site/terms']) ?> - <?= Html::a(Yii::t('app', 'Privacy Policy'),['/site/privacy']) ?></p>
        <p class="float-end"><?= Yii::t('app', 'Made with <span>Love</span> by <b>Huțanu Andrei</b> and <b>Roman David</b>') ?> 💚</p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
