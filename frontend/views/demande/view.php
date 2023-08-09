<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\KitEntreprise;
use common\models\KitLevel;

/* @var $this yii\web\View */
/* @var $model common\models\KitDemande */

$this->title = 'Dossier N°'.$model->code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Demandes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-md-8">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><?=$model->code?></h3>
                <div class="box-tools pull-right">
                    <?= (is_null($model->file)) ? '' : Html::a(Yii::t('app', '<i class="fa fa-fw fa-print"></i> Print'), ['report', 'id' => $model->id], ['class' => 'btn btn-primary', 'target' => '_blank']) ?>
                </div>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs pull-right">
                        <li><a href="#tab_2-2" data-toggle="tab">Pièces du dossier</a></li>
                        <li class="active"><a href="#tab_1-1" data-toggle="tab">Info. demande</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1-1">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    //'id',
                                    //'code',
                                    [
                                        'attribute'=>'',
                                        'label'=>Yii::t('app','Date of submission'),
                                        'value'=>$model->date_reception
                                    ],
                                    [
                                        'label'=>Yii::t('app','Demandeur'),
                                        'value'=>$model->employe->entreprise->raison_sociale
                                    ],
                                    [
                                        'label'=>Yii::t('app','Employé'),
                                        'value'=>$model->employe->nom.' '.$model->employe->prenom
                                    ],
                                    [
                                        'label'=>Yii::t('app','Type d\'autorisation'),
                                        'value'=>$model->typeAutorisation->libelle .' - '. $model->typeAutorisation->code
                                    ],
                                    [
                                        'format'=>'raw',
                                        'label'=>Yii::t('app','Statut'),
                                        'value'=> function($model){
                                            if($model->statut == 0)
                                                return '<span class="label label-warning">'.Yii::t('app','Encours de traitement').'</span>';
                                            elseif($model->statut%10 == 0)
                                                return '<span class="label label-danger">'.Yii::t('app','Rejeté').'</span>';
                                            else
                                                return '<span class="'.KitLevel::find()->where(['status_code' => $model->statut])->one()->status_colore.'">'.KitLevel::find()->where(['status_code' => $model->statut])->one()->level_name.'</span>';
                                        }

                                    ],
                                ],
                            ]) ?>
                        </div><!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2-2">
                            <div class="panel box box-success">
                                <div class="box-header with-border">
                                    <h4 class="box-title"><?=Yii::t('app','Liste des éléments fournis par le demandeur');?></h4>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <?php foreach($model->kitElementDemandes as $element): ?>
                                            <div class="form-group">
                                                <label style="display: block; width: 80%; float: left;">
                                                    <input type="checkbox" disabled class="flat-red" checked>
                                                    <?=$element->pieceFournir->nom ?>
                                                </label>
                                                <?php if(!is_null($element->fichier) || !empty($element->fichier)) : ?>
                                                    <?= Html::a(Yii::t('app','View Document'), [
                                                        'demande/pdf',
                                                        'id' => $element->pieceFournir->id,
                                                    ], [
                                                        'class' => 'btn btn-primary',
                                                        'target' => '_blank',
                                                    ]); ?>
                                                <?php endif; ?>
                                                <div style="clear: both;"></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if(!empty($model->typeAutorisation->kitCircuitValidations)) : ?>
                    <div style="margin: 25px 0 0 0">
                        <div class="row text-center justify-content-center mb-5">
                            <div class="col-xl-6">
                                <h2 class="font-weight-bold"><?=Yii::t('app','Circuit for processing your request')?></h2>
                                <p class="text-muted">&nbsp;</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="timeline-steps aos-init aos-animate" data-aos="fade-up">
                                    <?php foreach($model->typeAutorisation->kitCircuitValidations as $validationCircuit) : ?>
                                        <div class="timeline-step">
                                            <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2003">
                                                <div class="<?=($model->statut>=$validationCircuit->level->level_number)? 'inner-circle-ok' : 'inner-circle'?>"></div>
                                                <p class="h6 mt-3 mb-1"><?=$validationCircuit->level->level_description?></p>
                                                <!-- p class="h6 text-muted mb-0 mb-lg-0">Favland Founded</p -->
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
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
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>
