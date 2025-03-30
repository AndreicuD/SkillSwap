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
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100" data-bs-theme="light">
<?php $this->beginBody() ?>

<!--<div class="background" id="background"></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.fog.min.js"></script>
<script>
VANTA.FOG({
  el: "#background",
  mouseControls: true,
  touchControls: true,
  gyroControls: false,
  minHeight: 200.00,
  minWidth: 200.00,
  highlightColor: 0x0,
  midtoneColor: 0x3b205e,
  lowlightColor: 0x000000,
  baseColor: 0x0,
  blurFactor: 0.61,
  speed: 0.00,
  zoom: 0.80
})
</script>-->

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandImage' => '/img/logo-white.png',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md',
        ],
    ]);
    if (Yii::$app->user->isGuest) {
        $menuItems = [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Concerte', 'url' => ['/site/concerts']],
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            //['label' => 'Signup', 'url' => ['/user/signup']],
        ];
    } else {
        $menuItems = [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Propuneri Publice', 'url' => ['/proposal/public_proposals']],
            ['label' => 'Propuneri', 'url' => ['/proposal/proposals']],
            ['label' => 'Calendar', 'url' => ['/calendar/index']],
            ['label' => 'Concert', 'url' => ['/song/concert']],
            ['label' => 'Melodii', 'url' => ['/song/index']],
        ];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto mb-2 mb-md-0'],
        'items' => $menuItems,
    ]);
    if (Yii::$app->user->isGuest) {
        echo Html::tag('div',Html::a('Login',['/user/login'],['class' => ['btn btn-link login text-decoration-none']]),['class' => ['d-flex login_logoutbutton']]);
    } else {
        /*echo '<div class="btn-group">';
            echo '<a class="btn btn-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">';
                echo Yii::$app->user->identity->username;
            echo '</a>';
            echo '<ul class="dropdown-menu dropdown-menu-lg-end">';
                echo '<li><a class="dropdown-item" href="#">Pagina mea</a></li>';
                echo '<li><a class="dropdown-item" href="#">Statistici</a></li>';
                echo '<li><hr class="dropdown-divider"></li>';
                echo '<li><a class="dropdown-item" href="#">Setari</a></li>';
                echo Html::tag('li',Html::a('Logout',['/user/logout'],['class' => ['btn btn-link logout text-decoration-none']]),['class' => ['d-flex login_logoutbutton']]);
            echo '</ul>';
        echo '</div>';*/
        echo Html::tag('div',Html::a('Logout (' . Yii::$app->user->identity->username . ')',['/user/logout'],['class' => ['btn btn-link logout text-decoration-none']]),['class' => ['d-flex login_logoutbutton']]);
    }
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>


    
</main>

<footer class="footer footer-dark mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-end"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
