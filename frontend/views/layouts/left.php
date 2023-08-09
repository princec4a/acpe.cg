<?php
use common\models\User;
use common\models\KitEntreprise;

$anonymous = 'Anonymous';

$enterprise = KitEntreprise::findOne(['user_id' => Yii::$app->user->id]);
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?php $url = \Yii::getAlias('@web/anonymous.png'); ?>
                <img src="<?= (!is_null(KitEntreprise::find()->where(['user_id'=>Yii::$app->user->id])->one()))? KitEntreprise::find()->where(['user_id'=>Yii::$app->user->id])->one()->logo : $url ?>" class="img-circle"/>
            </div>
            <div class="pull-left info">
                <p><?= (!is_null(User::findIdentity(Yii::$app->user->id)))? User::findIdentity(Yii::$app->user->id)->username : $anonymous?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                   // ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                   // ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                   // ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                   // ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => Yii::t('app', 'Request management'),
                        'icon' => 'folder',
                        'url' => '#',
                        'visible' => (!is_null($enterprise) || !empty($enterprise)),
                        'items' => [
                            [
                                'label' => Yii::t('app', 'My requests'),
                                'icon' => 'circle-o',
                                'url' => ['/demande'],
                                'visible' => (!is_null($enterprise) || !empty($enterprise))
                            ],
                            [
                                'label' => Yii::t('app', 'My employees'),
                                'icon' => 'circle-o',
                                'url' => ['/employe'],
                                'visible' => (!is_null($enterprise) || !empty($enterprise))
                            ],
                            //['label' => Yii::t('app', 'Entreprises'), 'icon' => 'circle-o', 'url' => ['/management/entreprise'],],
                        ],
                    ],
                    /*[
                        'label' => Yii::t('app', 'Parametres'),
                        'icon' => 'gears',
                        'url' => '#',
                        'items' => [
                            ['label' => Yii::t('app', 'Type pièce ID'), 'icon' => 'circle-o', 'url' => ['/settings/type-piece-identite'],],
                            ['label' => Yii::t('app', 'Type autorisation'), 'icon' => 'circle-o', 'url' => ['/settings/type-autorisation'],],
                            ['label' => Yii::t('app', 'Type U.O'), 'icon' => 'circle-o', 'url' => ['/settings/type-uo'],],
                            ['label' => Yii::t('app', 'Type pièce à fournir'), 'icon' => 'circle-o', 'url' => ['/settings/piece-fournir'],],
                            ['label' => Yii::t('app', 'Unité organisationnelle'), 'icon' => 'circle-o', 'url' => ['/settings/uo'],],
                            ['label' => Yii::t('app', 'Constitution d\'un dossier'), 'icon' => 'circle-o', 'url' => ['/settings/type-autorisation-piece'],],
                            [
                                'label' => 'Level One',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'circle-o',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],*/
                    /*[
                        'label' => Yii::t('app', 'Administration'),
                        'icon' => 'users',
                        'url' => '#',
                        'items' => [
                            ['label' => Yii::t('app', 'Utilisateurs'), 'icon' => 'circle-o', 'url' => ['/user/admin'],],
                            ['label' => Yii::t('app', 'Permissions'), 'icon' => 'circle-o', 'url' => ['/user/rbac'],],
                            [
                                'label' => 'Level One',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'circle-o',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],*/
                ],
            ]
        ) ?>

    </section>

</aside>
