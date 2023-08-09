<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\KitEntreprise;

/* @var $this yii\web\View */
/* @var $model common\models\KitEmploye */

$this->title = $model->nom.' '.$model->prenom;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-md-8">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><?=$model->nom.' '.$model->prenom?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs pull-right">
                        <li><a href="#tab_4-2" data-toggle="tab">Téléphone</a></li>
                        <li><a href="#tab_3-2" data-toggle="tab">Identification</a></li>
                        <li><a href="#tab_2-2" data-toggle="tab">Info. professionnelle</a></li>
                        <li class="active"><a href="#tab_1-1" data-toggle="tab">Info. personnelle</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1-1">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    //'id',
                                    'photo:image',
                                    'nom',
                                    'nom_jeune_fille',
                                    'prenom',
                                    'date_naissance',
                                    'lieu_naissance:ntext',
                                    'email:ntext',
                                    [
                                        'label'=>Yii::t('app','Nationalité'),
                                        'value'=>$model->nationalite0->nompays,
                                    ],

                                ],
                            ]);
                            ?>
                        </div><!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2-2">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    [
                                        'label'=>Yii::t('app','Entreprise'),
                                        'value'=>$model->entreprise->raison_sociale,
                                    ],
                                    'lieu_travail',
                                    [
                                        'label'=>Yii::t('app','Ville de travail'),
                                        'value'=>$model->villeTravail->nom,
                                    ],
                                    'statut_medical',
                                    'type_contrat',
                                    'reference_contrat',
                                    'fonction',
                                    [
                                        'label'=>Yii::t('app','Date d\'embauche'),
                                        'value'=>$model->date_embauche,
                                    ],
                                    [
                                        'label'=>Yii::t('app','Date de fin contrat'),
                                        'value'=>$model->date_fin_contrat,
                                    ],
                                ],
                            ]);
                            ?>
                        </div>
                        <div class="tab-pane" id="tab_3-2">
                            <?php foreach($model->kitPersonneIdentites as $k=>$identite) : ?>
                                <div class="col-md-6" style="border-top: 1px solid #f4f4f4;border-bottom: 1px solid #f4f4f4; border-left: 1px solid #f4f4f4;  background-color: <?=($k%2==0)? '#f9f9f9' : '' ?>; padding: 8px;">
                                    <strong><?=Yii::t('app',$identite->typePiece->libelle)?></strong>
                                </div>
                                <div class="col-md-6" style="border-top: 1px solid #f4f4f4;border-bottom: 1px solid #f4f4f4; border-left: 1px solid #f4f4f4;  background-color: <?=($k%2==0)? '#f9f9f9' : '' ?>; padding: 8px;">
                                    <?=$identite->numero?>
                                </div>
                                <div class="clearfix "></div>
                            <?php endforeach; ?>
                        </div>
                        <div class="tab-pane" id="tab_4-2">
                            <?php foreach($model->kitTelephones as $k=>$phone) : ?>
                                <div class="col-md-6" style="border-top: 1px solid #f4f4f4;border-bottom: 1px solid #f4f4f4; border-left: 1px solid #f4f4f4;  background-color: <?=($k%2==0)? '#f9f9f9' : '' ?>; padding: 8px;">
                                    <strong><?=Yii::t('app','Téléphone ').($k+1)?></strong>
                                </div>
                                <div class="col-md-6" style="border-top: 1px solid #f4f4f4;border-bottom: 1px solid #f4f4f4; border-left: 1px solid #f4f4f4;  background-color: <?=($k%2==0)? '#f9f9f9' : '' ?>; padding: 8px;">
                                    <?=$phone->numero?>
                                </div>
                                <div class="clearfix "></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Menu</h3>
            </div>
            <div class="box-body">
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-home']) . Yii::t('app', 'Accueil'), ['/entreprise/update','id' => (!is_null(KitEntreprise::find()->where(['user_id'=>Yii::$app->user->id])->one()))? KitEntreprise::find()->where(['user_id'=>Yii::$app->user->id])->one()->id : ''], ['class' => 'btn btn-app'])?>
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-bars']) . Yii::t('app', 'Liste'), ['index'], ['class' => 'btn btn-app'])?>
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-edit']) . Yii::t('app', 'Editer'), ['update', 'id' => $model->id], ['class' => 'btn btn-app'])?>
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-times']) . Yii::t('app', 'Supprimer'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-app',
                    'data' => [
                        'confirm' => Yii::t('app', 'êtes vous sûr de supprimer cet élément?'),
                        'method' => 'post',
                    ],
                ])?>
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-envelope'])
                    . Html::tag('span', '67', ['class' => 'badge bg-aqua'])
                    . Yii::t('app', 'Messagerie'), ['index'], ['class' => 'btn btn-app'])?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>