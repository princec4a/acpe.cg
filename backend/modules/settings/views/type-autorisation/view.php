<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $model common\models\KitTypeAutorisation */

$this->title = $model->libelle;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Type Autorisation'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->code;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-md-8">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><?=$model->libelle?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'code',
                        'libelle',
                        [
                            'attribute'=>'duree_validite',
                            'value' => function($model){
                                if($model->duree_validite > 1){
                                    return (StringHelper::endsWith(strtolower($model->type_duree), 's'))? $model->duree_validite .' '.strtolower($model->type_duree) : $model->duree_validite .' '.strtolower($model->type_duree) . 's';
                                }else{
                                    return $model->duree_validite .' '.strtolower($model->type_duree);
                                }
                            }
                        ],
                        'nationalite',
                        'prix',
                        //'created_at',
                        //'updated_at',
                    ],
                ]) ?>

                <?php if(!empty($model->kitCircuitValidations)) : ?>
                <div style="margin: 25px 0 0 0">
                    <div class="row text-center justify-content-center mb-5">
                        <div class="col-xl-6">
                            <h2 class="font-weight-bold"><?=Yii::t('app','Circuit de validation')?></h2>
                            <p class="text-muted">&nbsp;</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="timeline-steps aos-init aos-animate" data-aos="fade-up">
                                <?php foreach($model->kitCircuitValidations as $validationCircuit) : ?>
                                    <div class="timeline-step">
                                        <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2003">
                                            <div class="inner-circle"></div>
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
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-plus']) . Yii::t('app', 'Ajouter'), ['create'], ['class' => 'btn btn-app'])?>
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-bars']) . Yii::t('app', 'Liste'), ['index'], ['class' => 'btn btn-app'])?>
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-edit']) . Yii::t('app', 'Editer'), ['update', 'id' => $model->id], ['class' => 'btn btn-app'])?>
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-times']) . Yii::t('app', 'Supprimer'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-app',
                    'data' => [
                        'confirm' => Yii::t('app', 'êtes vous sûr de supprimer cet élément?'),
                        'method' => 'post',
                    ],
                ])?>
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-home']) . Yii::t('app', 'Accueil'), ['/'], ['class' => 'btn btn-app'])?>
                <?php /*
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-user']) . Yii::t('app', 'Mon profil'), ['index'], ['class' => 'btn btn-app'])?>
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-bullhorn'])
                    . Html::tag('span', '67', ['class' => 'badge bg-teal'])
                    . Yii::t('app', 'Demandes'), ['index'], ['class' => 'btn btn-app'])?>
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-envelope'])
                    . Html::tag('span', '67', ['class' => 'badge bg-aqua'])
                    . Yii::t('app', 'Messagerie'), ['index'], ['class' => 'btn btn-app'])?>
                */ ?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>
