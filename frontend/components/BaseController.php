<?php

namespace frontend\components;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
    public function beforeAction($action)
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->identity->applyDailyBonus();
        }

        return parent::beforeAction($action);
    }
}
