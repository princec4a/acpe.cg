<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
<div class="verify-email">
    <p><?= Yii::t('app','Hello').' '.  Html::encode($user->username) ?>,</p>

    <p><?= Yii::t('app','You have created your online space. Please click on the following link to activate your account and customize your password : ')?></p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div>
