<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use budyaga\users\components\AuthorizationWidget;


/* @var $this \yii\web\View */
/* @var $content string */

dmstr\web\AdminLteAsset::register($this);
$this->title = Yii::t('users', 'LOGIN');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="login-page" style="background-color: rgb(236, 240, 245);">

    <?php $this->beginBody() ?>

    <?= AuthorizationWidget::widget() ?>

    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
