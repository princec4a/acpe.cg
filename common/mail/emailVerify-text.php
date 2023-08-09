<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
<?= Yii::t('app','Hello').' '.  $user->username ?>,

<?= Yii::t('app','You have created your online space. Please click on the following link to activate your account and customize your password : ')?>

<?= $verifyLink ?>
