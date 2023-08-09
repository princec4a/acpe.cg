<?php
use budyaga\users\models\User;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?php $url = \Yii::getAlias('@web/uploads/user/photo/nophoto.png'); ?>
                <img src="<?= (!is_null(User::findIdentity(Yii::$app->user->id)))? User::findIdentity(Yii::$app->user->id)->photo: $url ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= User::findIdentity(Yii::$app->user->id)->username?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
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
                        'label' => Yii::t('app', 'Gestion des dossiers'),
                        'icon' => 'folder',
                        'url' => '#',
                        'visible' => Yii::$app->user->can('Verificateur')
                                    || Yii::$app->user->can('Valideur1')
                                    || Yii::$app->user->can('Valideur2')
                                    || Yii::$app->user->can('Valideurfinal')
                                    || Yii::$app->user->can('Enterprise')
                                    || Yii::$app->user->can('Demande')
                                    || Yii::$app->user->can('Employe'),
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Demandes'),
                                'icon' => 'circle-o',
                                'url' => ['/management/demande'],
                                'visible' => Yii::$app->user->can('Demande') || Yii::$app->user->can('Verificateur') || Yii::$app->user->can('Valideur1') || Yii::$app->user->can('Valideur2') || Yii::$app->user->can('Valideurfinal')
                            ],
                            [
                                'label' => Yii::t('app', 'Employes'),
                                'icon' => 'circle-o',
                                'url' => ['/management/employe'],
                                'visible' => Yii::$app->user->can('Employe') || Yii::$app->user->can('Verificateur') || Yii::$app->user->can('Valideur1') || Yii::$app->user->can('Valideur2') || Yii::$app->user->can('Valideurfinal')
                            ],
                            [
                                'label' => Yii::t('app', 'Entreprises'),
                                'icon' => 'circle-o',
                                'url' => ['/management/entreprise'],
                                'visible' => Yii::$app->user->can('Enterprise') || Yii::$app->user->can('Verificateur') || Yii::$app->user->can('Valideur1') || Yii::$app->user->can('Valideur2') || Yii::$app->user->can('Valideurfinal')
                            ],
                            [
                                'label' => Yii::t('app', 'Accusé de réception'),
                                'icon' => 'circle-o',
                                'url' => ['/management/acknowledgment'],
                                'visible' => Yii::$app->user->can('AccuseReception') || Yii::$app->user->can('Verificateur') || Yii::$app->user->can('Valideur1') || Yii::$app->user->can('Valideur2') || Yii::$app->user->can('Valideurfinal')
                            ],
                        ],
                    ],
                    [
                        'label' => Yii::t('app', 'Parametres'),
                        'icon' => 'gears',
                        'url' => '#',
                        'visible' => Yii::$app->user->can('Administration') || Yii::t('app', 'Parametres'),
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Type pièce ID'),
                                'icon' => 'circle-o',
                                'url' => ['/settings/type-piece-identite'],
                                //'visible' => Yii::$app->user->can('Administration')
                            ],
                            [
                                'label' => Yii::t('app', 'Type autorisation'),
                                'icon' => 'circle-o',
                                'url' => ['/settings/type-autorisation'],
                                //'visible' => Yii::$app->user->can('Administration')
                            ],
                            [
                                'label' => Yii::t('app', 'Type U.O'),
                                'icon' => 'circle-o',
                                'url' => ['/settings/type-uo'],
                                //'visible' => Yii::$app->user->can('Administration')
                            ],
                            [
                                'label' => Yii::t('app', 'Type pièce à fournir'),
                                'icon' => 'circle-o',
                                'url' => ['/settings/piece-fournir'],
                                //'visible' => Yii::$app->user->can('Administration')
                            ],
                            [
                                'label' => Yii::t('app', 'Unité organisationnelle'),
                                'icon' => 'circle-o',
                                'url' => ['/settings/uo'],
                                //'visible' => Yii::$app->user->can('Administration')
                            ],
                            [
                                'label' => Yii::t('app', 'Constitution d\'un dossier'),
                                'icon' => 'circle-o',
                                'url' => ['/settings/type-autorisation-piece'],
                                //'visible' => Yii::$app->user->can('Administration')
                            ],
                            [
                                'label' => Yii::t('app', 'Niveau de controle'),
                                'icon' => 'circle-o',
                                'url' => ['/settings/level'],
                                //'visible' => Yii::$app->user->can('Administration')
                            ],
                            //['label' => Yii::t('app', 'Circuit de validation'), 'icon' => 'circle-o', 'url' => ['/settings/circuit-validation'],],
                            /*[
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
                            ],*/
                        ],
                    ],
                    [
                        'label' => Yii::t('app', 'Administration'),
                        'icon' => 'users',
                        'url' => '#',
                        'visible' => Yii::$app->user->can('administrator'),
                        'items' => [
                            ['label' => Yii::t('app', 'Utilisateurs'), 'icon' => 'circle-o', 'url' => ['/user/admin'],],
                            ['label' => Yii::t('app', 'Permissions'), 'icon' => 'circle-o', 'url' => ['/user/rbac'],],
                            /*[
                                'label' => Yii::t('app', 'Departement utilisateur'),
                                'icon' => 'circle-o',
                                'url' => ['/settings/user-departement'],
                                //'visible' => Yii::$app->user->can('Administration')
                            ],*/
                            /*[
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
                            ],*/
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
