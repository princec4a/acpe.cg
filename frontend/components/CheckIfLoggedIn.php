<?php
namespace frontend\components;

use yii\base\Behavior;
use yii\web\Application;

class CheckIfLoggedIn extends Behavior
{
    public function events()
    {
        return [
          Application::EVENT_BEFORE_REQUEST => 'changeLanguage'
        ];
    }

    public function changeLanguage()
    {
        if(\Yii::$app->getRequest()->getCookies()->has('lang')){
            \Yii::$app->language = \Yii::$app->getRequest()->getCookies()->getValue('lang');
        }
    }

}