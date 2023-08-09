<?php
use yii\helpers\Html;
use budyaga\users\models\User;
use yii\helpers\Url;
use machour\yii2\notifications\widgets\NotificationsWidget;

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

$url = \Yii::getAlias('@web/acpe-logo.png');

?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">ACPE</span><span class="logo-lg"><img src="' . $url. '" style="width: 104px;"></span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

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
                        <?php $url = \Yii::getAlias('@web/uploads/user/photo/nophoto.png'); ?>
                        <img src="<?= (!is_null(User::findIdentity(Yii::$app->user->id)) || !empty(User::findIdentity(Yii::$app->user->id)))? User::findIdentity(Yii::$app->user->id)->photo : $url ?>" class="user-image" alt=""/>
                        <span class="hidden-xs"><?= User::findIdentity(Yii::$app->user->id)->username?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= (!is_null(User::findIdentity(Yii::$app->user->id)->photo))? User::findIdentity(Yii::$app->user->id)->photo : $url ?>" class="img-circle"
                                 alt="<?= User::findIdentity(Yii::$app->user->id)->username?>"/>

                            <p>
                                <?= User::findIdentity(Yii::$app->user->id)->username?>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a(
                                    Yii::t('app','Profil'),
                                    ['/profile'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    Yii::t('app','Déconnexion'),
                                    ['/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
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
