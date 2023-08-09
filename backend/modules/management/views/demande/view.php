<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\KitLevel;
use yii\bootstrap\Modal;
use yii\widgets\DetailView;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use budyaga\users\models\AuthAssignment;
use common\models\KitCircuitValidation;
use common\models\KitRejet;
use common\models\KitValidationDemande;

/* @var $this yii\web\View */
/* @var $model common\models\KitDemande */

$this->title = 'Dossier N° '.$model->code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Demandes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$authAssignment = ArrayHelper::map(AuthAssignment::find()->where(['user_id'=>Yii::$app->user->id])->all(), 'item_name','user_id');
$level = null;
foreach(KitLevel::find()->all() as $level) {
    if (array_key_exists(ucwords(strtolower($level->level_name)), $authAssignment)) {
        $level = $level->level_number;
        break;
    }
}

?>
<div class="row">
    <div class="col-md-8">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><?=$model->code?></h3>
                <div class="box-tools pull-right">
                    <?= ($model->statut%10 != 0 && $model->statut == intval(KitCircuitValidation::getMaxLevel($model->type_autorisation_id)) && is_null($model->file_name)) ? Html::a(Yii::t('app', '<i class="fa fa-fw fa-gears"></i> Generate document'), ['report', 'id' => $model->id], ['class' => 'btn btn-primary', 'target' => '_blank']) : '' ?>
                    <?= ($model->statut%10 != 0 && $model->statut == KitCircuitValidation::getMaxLevel($model->type_autorisation_id) && empty(KitValidationDemande::find()->where(['demande_id'=>$model->id, 'level'=>$model->statut])->one()->file_name)) ? Html::a(Yii::t('app', '<i class="fa fa-fw fa-print"></i> Imprimer la lettre'), ['validate-letter', 'id' => $model->id, 'status'=>$model->statut], ['class' => 'btn btn-success', 'target' => '_blank']) : '' ?>
                    <?= ($model->statut%10 == 0 && $model->statut/10 == KitCircuitValidation::getMaxLevel($model->type_autorisation_id) && empty(KitRejet::find()->where(['demande_id'=>$model->id, 'level'=>$model->statut])->one()->file_name)) ? Html::a(Yii::t('app', '<i class="fa fa-fw fa-print"></i> Imprimer la lettre'), ['reject-letter', 'id' => $model->id, 'status'=>$model->statut], ['class' => 'btn btn-danger', 'target' => '_blank']) : '' ?>
                    <?= ($model->statut%10 != 0 && (is_null($model->file_name) || empty($model->file_name))) ? Html::a(Yii::t('app', '<i class="fa fa-fw fa-print"></i> Print'), ['view-pdf', 'id' => $model->id], ['class' => 'btn btn-primary', 'target' => '_blank']) : '' ?>
                    <?php if(Yii::$app->user->can('demandeReject', ['user' => $model]) && ($level > (($model->statut%10==0)? $model->statut/10: $model->statut ))) : ?>
                        <?php
                            Modal::begin([
                                'header' => '<h2>'.Yii::t('app','Êtes vous sûr de vouloir rejeter ce dossier').'</h2>',
                                'toggleButton' => [
                                    'label' => Yii::t('app', '<i class="fa fa-fw fa-close"></i> Reject'),
                                    'class' => 'btn btn-danger'
                                ],
                            ]);

                            ActiveForm::begin([
                                'action' => 'reject',
                                'method' => 'post',
                                'id' => 'form-reject'.$model->id
                            ]);
                            ?>
                            <?= Html::hiddenInput('KitRejet[demande_id]',$model->id,['placeholder' => Yii::t('app','Observation'),'class' => 'form-control']);?>
                            <div class="form-group"><?= Html::textarea('KitRejet[motif]','',['placeholder' => Yii::t('app','Motif'),'class' => 'form-control', 'required'=>'required']);?></div>
                            <div class="box-footer">
                                <?= Html::submitButton(Yii::t('app','Oui'), ['class' => 'btn btn-success']);?>
                                <?= Html::button(Yii::t('app','Non'), ['class' => 'btn btn-default pull-right', 'data-dismiss'=>'modal', 'aria-hidden'=>'true']);?>
                            </div>
                            <?php
                            ActiveForm::End();
                            Modal::end();
                        ?>
                    <?php endif ?>
                    <?php if(Yii::$app->user->can('demandeValidate', ['user' => $model]) && ($level > (($model->statut%10==0)? $model->statut/10: $model->statut ))) : ?>
                        <?php
                            Modal::begin([
                                'header' => '<h2 class="">'.Yii::t('app','Êtes vous sûr de vouloir valider ce dossier').'</h2>',
                                'toggleButton' => [
                                    'label' => Yii::t('app', '<i class="fa fa-fw fa-check"></i> Validate'),
                                    'class' => 'btn btn-success'
                                ],
                            ]);

                            ActiveForm::begin([
                                'action' => 'validate',
                                'method' => 'post',
                                'id' => 'form-validate'.$model->id
                            ]);
                            ?>
                            <?= Html::hiddenInput('KitValidationDemande[demande_id]',$model->id,['placeholder' => Yii::t('app','Observation'),'class' => 'form-control']);?>
                            <div class="form-group"><?= Html::textarea('KitValidationDemande[observation]','',['placeholder' => Yii::t('app','Observation'),'class' => 'form-control']);?></div>
                            <div class="box-footer">
                                <?= Html::submitButton(Yii::t('app','Oui'), ['class' => 'btn btn-success']);?>
                                <?= Html::button(Yii::t('app','Non'), ['class' => 'btn btn-default pull-right', 'data-dismiss'=>'modal', 'aria-hidden'=>'true']);?>
                            </div>
                            <?php
                            ActiveForm::End();
                            Modal::end();
                        ?>
                    <?php endif ?>
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
                                    'date_reception',
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
                                        'attribute'=>'signed_date',
                                        'value'=>(is_null($model->signed_date))? '' : date('d-m-Y', $model->signed_date)
                                    ],
                                    [
                                        'attribute'=>'expiration_date',
                                        'value'=>(is_null($model->expiration_date))? '' : date('d-m-Y', $model->expiration_date)
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
                                    [
                                        'attribute'=>'file',
                                        'format'=>'raw',
                                        'label'=>Yii::t('app','Lettre'),
                                        'value'=>function($model){
                                            if($model->statut%10==0 && !empty(KitRejet::find()->where(['demande_id'=>$model->id, 'level'=>$model->statut])->one()->file_name)) {
                                                $id = KitRejet::find()->where(['demande_id' => $model->id, 'level' => $model->statut])->one()->id;
                                                return Html::a(Yii::t('app', '<i class="fa fa-fw fa-print"></i> visualiser la lettre'), ['/settings/reject/view-pdf', 'id' => $id], ['class' => 'btn btn-danger', 'target' => '_blank']);
                                            }elseif($model->statut%10!=0 && !empty(KitValidationDemande::find()->where(['demande_id'=>$model->id, 'level'=>$model->statut])->one()->file_name)){
                                                $id = KitValidationDemande::find()->where(['demande_id'=>$model->id, 'level'=>$model->statut])->one()->id;
                                                return Html::a(Yii::t('app', '<i class="fa fa-fw fa-print"></i> visualiser la lettre'), ['/settings/validation-demande/view-pdf', 'id' => $id], ['class' => 'btn btn-success', 'target' => '_blank']);
                                            }
                                        }
                                    ],

                                    //'create_at',
                                    //'update_at',
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
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Menu</h3>
            </div>
            <div class="box-body">
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-home']) . Yii::t('app', 'Accueil'), ['/'], ['class' => 'btn btn-app'])?>
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

        <?php if($model->statut%10 != 0 && $model->statut == KitCircuitValidation::getMaxLevel($model->type_autorisation_id)): ?>
        <div class="box box-warning">
            <div class="box-header with-border">
                <i class="fa fa-file-text"></i><?=Yii::t('app','Attach the signed document')?>
                <div class="clearfix"></div>
            </div>
            <div class="box-body">
                <div class="form-telephone-body">
                    <div class="form-telephone-item">
                        <?php $form = ActiveForm::begin([
                            'action' => 'import',
                            'method' => 'post',
                            'options' => [
                                'enctype' => 'multipart/form-data'
                            ]
                        ]) ?>
                        <?= $form->field($uploadModel, 'file')->fileInput() ?>
                        <?= $form->field($uploadModel, 'fileLetter')->fileInput() ?>
                        <?= $form->field($uploadModel, 'signed')->checkbox(['label' => Yii::t('app', 'Signed document ?')])?>
                        <?= $form->field($uploadModel, 'id')->hiddenInput(['value'=> $model->id])->label(false) ?>
                        <?= $form->field($uploadModel, 'status')->hiddenInput(['value'=> $model->statut])->label(false) ?>
                        <?= Html::submitButton(Html::tag('i', '', ['class' => 'fa fa-fw fa-floppy-o']) .' '.Yii::t('app', 'Save the signed document'), ['class' => 'btn btn-success btn-block btn-flat']) ?>
                        <?php ActiveForm::end() ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if($model->statut%10==0 && ($model->statut/10)==KitCircuitValidation::getMaxLevel($model->type_autorisation_id)): ?>
            <div class="box box-danger">
                <div class="box-header with-border">
                    <i class="fa fa-file-text"></i><?=Yii::t('app','Attach the signed document')?>
                    <div class="clearfix"></div>
                </div>
                <div class="box-body">
                    <div class="form-telephone-body">
                        <div class="form-telephone-item">
                            <?php $form = ActiveForm::begin([
                                'action' => 'import-reject',
                                'method' => 'post',
                                'options' => [
                                    'enctype' => 'multipart/form-data'
                                ]
                            ]) ?>
                            <?= $form->field($uploadRejectModel, 'fileLetter')->fileInput(); ?>
                            <?= $form->field($uploadRejectModel, 'signed')->checkbox(['label' => Yii::t('app', 'Signed document ?')])?>
                            <?= $form->field($uploadRejectModel, 'id')->hiddenInput(['value'=> $model->id])->label(false) ?>
                            <?= $form->field($uploadRejectModel, 'status')->hiddenInput(['value'=> $model->statut])->label(false) ?>
                            <?= Html::submitButton(Html::tag('i', '', ['class' => 'fa fa-fw fa-floppy-o']) .' '.Yii::t('app', 'Save the signed document'), ['class' => 'btn btn-success btn-block btn-flat']) ?>
                            <?php ActiveForm::end() ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
