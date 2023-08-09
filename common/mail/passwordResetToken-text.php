<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $token->token]);
?>
<?= Yii::t('app','Hello').' '. $user->username ?>,

<?=Yii::t('app','Follow the link below to reset your password:')?>

<?= $resetLink ?>
