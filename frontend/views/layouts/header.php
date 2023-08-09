<?php
use yii\helpers\Html;
use common\models\User;
use common\models\KitEntreprise;
use yii\helpers\Url;
use machour\yii2\notifications\widgets\NotificationsWidget;
use Da\QrCode\QrCode;

/* @var $this \yii\web\View */
/* @var $content string */

NotificationsWidget::widget([
    'theme' => NotificationsWidget::THEME_GROWL,
    'clientOptions' => [
        'location' => 'br',
    ],
    'counters' => [
        '.notifications-header-count',
        '.notifications-icon-count'
    ],
    'markAllSeenSelector' => '#notification-seen-all',
    'listSelector' => '#notifications',
]);

$anonymous = 'Anonymous';

$url = \Yii::getAlias('@web/acpe-logo.png');

$enterprise = KitEntreprise::findOne(['user_id' => Yii::$app->user->id]);
$encryption = User::findIdentity(Yii::$app->user->id)->qrcode;
$qrCode = (is_null($encryption) || empty($encryption))? '' : (new QrCode($encryption))
    ->setSize(250)
    ->setMargin(5)
    ->useForegroundColor(2, 1, 1);
//->useForegroundColor(11, 119, 189);

?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">ACPE</span><span class="logo-lg"><img src="' . $url. '" style="width: 104px;"></span>',
        (is_null($enterprise) || empty($enterprise))?
            ['entreprise/create'] :
            ['entreprise/update', 'id' => KitEntreprise::findOne(['user_id' => Yii::$app->user->id])->id],
        ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success notifications-icon-count">0</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"><?=Yii::t('app','You have')?> <span class="notifications-header-count">0</span> messages</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <div id="notifications"></div>
                            </ul>
                        </li>
                        <!--<li class="footer"><a href="#" id="notification-seen-all">See All Messages</a></li>-->
                    </ul>
                </li>
                <!--<li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning">10</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 10 notifications</li>
                        <li>
                            <ul class="menu">
                                <li>
                                    <a href="#">
                                        <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-warning text-yellow"></i> Very long description here that may
                                        not fit into the page and may cause design problems
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-users text-red"></i> 5 new members joined
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-user text-red"></i> You changed your username
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">View all</a></li>
                    </ul>
                </li>-->
                <!-- Tasks: style can be found in dropdown.less -->
                <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="font-size: 16px">
                        <i class="fa fa-globe" aria-hidden="true"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li style="height: 92px;">
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu" style="height: 92px;">
                                <?php foreach(Yii::$app->params['languages'] as $key => $language) : ?>
                                    <li><!-- Task item -->
                                        <a href="<?=Url::to(['/site/language', 'lang'=> $key])?>" id ="<?= $key;?>">
                                            <h3><?=Yii::t('app',$language);?></h3>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    </ul>
                </li>
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php $url = \Yii::getAlias('@web/anonymous.png'); ?>
                        <img src="<?= (!is_null(KitEntreprise::find()->where(['user_id'=>Yii::$app->user->id])->one()))? KitEntreprise::find()->where(['user_id'=>Yii::$app->user->id])->one()->logo : $url ?>" class="user-image" alt=""/>
                        <span class="hidden-xs"><?= (!is_null(User::findIdentity(Yii::$app->user->id)))? User::findIdentity(Yii::$app->user->id)->username : $anonymous ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <?php if(!is_null($encryption) && !empty($encryption)) : ?>
                            <img src="<?=$qrCode->writeDataUri()?>" style="width: 100%; height: 100%"/>
                            <p><?=Yii::t('app','Scan Qrcode with mobile app, to login')?></p>
                            <?php endif; ?>
                        </li>
                        <li class="user-footer">
                            <?php /*
                            <div class="pull-left">
                                <?= Html::a(
                                    Yii::t('app','Profile'),
                                    ['/site/profile'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                            */ ?>
                            <div>
                                <?= Html::a(
                                    Yii::t('app','Déconnexion'),
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-danger btn-flat col-md-12']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
