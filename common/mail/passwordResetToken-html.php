<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $token->token]);
?>
<div class="password-reset">
    <p><?= Yii::t('app','Hello').' '. Html::encode($user->username) ?>,</p>

    <p><?=Yii::t('app','Follow the link below to reset your password:')?></p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
