<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Google Font: Source Sans Pro -->
    <?php $this->registerCssFile("https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"); ?>
    <?php $this->registerCssFile("https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"); ?>
    <!-- Morris chart -->
    <?php $this->registerCssFile("@web/plugins/morris/morris.css"); ?>
    <!-- jvectormap -->
    <?php $this->registerCssFile("@web/plugins/jvectormap/jquery-jvectormap-1.2.2.css"); ?>
    <!-- Date Picker -->
    <?php //$this->registerCssFile("@web/plugins/datepicker/datepicker3.css"); ?>
    <!-- Daterange picker -->
    <?php $this->registerCssFile("@web/plugins/daterangepicker/daterangepicker-bs3.css"); ?>
    <!-- bootstrap wysihtml5 - text editor -->
    <?php $this->registerCssFile("@web/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"); ?>
    <!-- Theme style -->
    <?php $this->registerCssFile("@web/dist/css/AdminLTE.min.css"); ?>
    <!-- iCheck -->
    <?php $this->registerCssFile("@web/plugins/iCheck/square/blue.css"); ?>
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<body class="sidebar-mini skin-black">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <?php $this->registerJsFile("https://code.jquery.com/ui/1.11.4/jquery-ui.min.js",['depends' => [yii\web\JqueryAsset::className()]]); ?>
    <?php $this->registerJsFile("@web/plugins/morris/morris.min.js",['depends' => [yii\web\JqueryAsset::className()]]); ?>
    <?php $this->registerJsFile("@web/plugins/sparkline/jquery.sparkline.min.js",['depends' => [yii\web\JqueryAsset::className()]]); ?>
    <!-- jvectormap -->
    <?php $this->registerJsFile("@web/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js",['depends' => [yii\web\JqueryAsset::className()]]); ?>
    <?php $this->registerJsFile("@web/plugins/jvectormap/jquery-jvectormap-world-mill-en.js",['depends' => [yii\web\JqueryAsset::className()]]); ?>
    <!-- jQuery Knob Chart -->
    <?php $this->registerJsFile("@web/plugins/knob/jquery.knob.js",['depends' => [yii\web\JqueryAsset::className()]]); ?>
    <?php $this->registerJsFile("@web/plugins/daterangepicker/daterangepicker.js",['depends' => [yii\web\JqueryAsset::className()]]); ?>
    <!-- datepicker -->
    <?php //$this->registerJsFile("@web/plugins/datepicker/bootstrap-datepicker.js",['depends' => [yii\web\JqueryAsset::className()]]); ?>
    <!-- Bootstrap WYSIHTML5 -->
    <?php $this->registerJsFile("@web/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js",['depends' => [yii\web\JqueryAsset::className()]]); ?>
    <!-- Slimscroll -->
    <?php $this->registerJsFile("@web/plugins/slimScroll/jquery.slimscroll.min.js",['depends' => [yii\web\JqueryAsset::className()]]); ?>
    <!-- FastClick -->
    <?php $this->registerJsFile("@web/plugins/fastclick/fastclick.min.js",['depends' => [yii\web\JqueryAsset::className()]]); ?>
    <?php $this->registerJsFile("@web/dist/js/app.min.js",['depends' => [yii\web\JqueryAsset::className()]]); ?>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <?php $this->registerJsFile("@web/dist/js/pages/dashboard.js",['depends' => [yii\web\JqueryAsset::className()]]); ?>
    <!-- AdminLTE for demo purposes -->
    <?php $this->registerJsFile("@web/dist/js/demo.js",['depends' => [yii\web\JqueryAsset::className()]]); ?>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
