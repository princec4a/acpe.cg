<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KitEmploye */

$this->title = Yii::t('app', 'Modification Employé', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employés'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nom.' '.$model->prenom, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Modification');
?>
<div class="row">
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?=Yii::t('app', 'Modifier l\'employé : '). $model->nom.' '.$model->prenom?></h3>
            </div>
            <div class="box-body">
                <?= $this->render('_form', [
                    'model' => $model,
                    'modelsOptionValue' => $modelsOptionValue,
                    'modelsTelephone' => $modelsTelephone
                ]) ?>
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
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-plus']) . Yii::t('app', 'Ajouter'), ['create'], ['class' => 'btn btn-app'])?>
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-envelope'])
                    . Html::tag('span', '67', ['class' => 'badge bg-aqua'])
                    . Yii::t('app', 'Messagerie'), ['index'], ['class' => 'btn btn-app'])?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>
