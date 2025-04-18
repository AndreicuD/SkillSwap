<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="<?= Yii::$app->requestedRoute == 'site/index' ? '' : 'h-100' ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Antonio:wght@100..700&family=Funnel+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100" data-bs-theme="light">
<?php $this->beginBody() ?>

<?php
    NavBar::begin([
        //'brandImage' => '/img/logo-dark.png',
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'id' => 'navbar',
            'class' => 'navbar bg-primary navbar-expand-md sticky-top',

        ],
    ]);
?>

<!-- keep this if you also want a logo by the navbar label -->
<!--
<a class="navbar-brand" href="/site/index">
    <img src="/img/logo-dark.png" alt="Logo" class="d-inline-block align-text-top">
    <a class="navbar-brand"><?= Yii::$app->name ?></a>
</a>
-->

<?php
    if (Yii::$app->user->isGuest) {
        $menuItems = [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            //['label' => 'Signup', 'url' => ['/user/signup']],
        ];
    } else {
        $menuItems = [
            ['label' => 'Home', 'url' => ['/site/index']],
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
        echo '<div class="btn-group">';
            echo '<a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">';
                echo Yii::$app->user->identity->username;
            echo '</a>';
            echo '<ul class="dropdown-menu dropdown-menu-lg-end">';
                echo '<li><a class="dropdown-item" href="#">Pagina mea</a></li>';
                echo '<li><a class="dropdown-item" href="#">Statistici</a></li>';
                echo '<li><hr class="dropdown-divider"></li>';
                echo '<li><a class="dropdown-item" href="#">Setari</a></li>';
                echo Html::tag('li',Html::a('Logout',['/user/logout'],['class' => ['btn btn-link logout text-decoration-none']]),['class' => ['d-flex login_logoutbutton']]);
            echo '</ul>';
        echo '</div>';
        //echo Html::tag('div',Html::a('Logout (' . Yii::$app->user->identity->username . ')',['/user/logout'],['class' => ['btn btn-link logout text-decoration-none']]),['class' => ['d-flex login_logoutbutton']]);
    }
    NavBar::end();
?>

<!-- hero if on the index page -->
<?php if(Yii::$app->requestedRoute == "site/index" && Yii::$app->user->isGuest){ ?>
    <div class="hero user-select-none">
        <div class="hero-text" id="hero-text">
            <h1>SkillSwap</h1>
            <p><?= Yii::t('app', 'Learn new skills faster than ever before!') ?></p>
        </div>
        <div class="hero-arrow-anim">
            <a class="hero-arrow" href="#content">
                <svg xmlns="http://www.w3.org/2000/svg"  width="60"  height="60"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-compact-down down-arrow"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 11l8 3l8 -3" /></svg>
            </a>
        </div>
    </div>
<?php } ?>

<main role="main" class="flex-shrink-0">
    <div class="<?= Yii::$app->requestedRoute == 'site/index' ? '' : 'container' ?>">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer footer-light mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-end">Made with <span>Love</span> by <b>Huțanu Andrei</b> and <b>Roman David</b> 💚</p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
