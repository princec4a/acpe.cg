<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\KitAcknowledgment */

$this->title = 'Accusé de réception de la demande '.$model->demande->code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Accusés de réception'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-md-8">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><?=$this->title?></h3>
                <div class="box-tools pull-right">
                    <?= Yii::$app->user->can('acknowledgmentPrint', ['user' => $model])  ? Html::a(Yii::t('app', '<i class="fa fa-fw fa-print"></i> Print'), ['view-pdf', 'id' => $model->id], ['class' => 'btn btn-primary', 'target' => '_blank']) : '' ?>
                </div>
            </div>
            <div class="box-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'code',
                        [
                            'attribute'=>'demande_id',
                            'value'=>$model->demande->code
                        ],
                        [
                            'label'=>'Employé',
                            'value'=>$model->demande->employe->nom .' '.$model->demande->employe->prenom
                        ],
                        [
                            'label'=>'Type d\'autorisation',
                            'value'=>$model->demande->typeAutorisation->libelle
                        ],
                    ],
                ]) ?>
                <div class="form-group">
                    <?php foreach($model->kitElementDossierPhysiques as $element): ?>
                        <div class="form-group">
                            <label style="display: block; width: 80%; float: left;">
                                <input type="checkbox" disabled class="flat-red" checked>
                                <?=$element->pieceFournie->nom ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
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
    </div>
</div>
