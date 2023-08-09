<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KitTypePieceIdentite */

$this->title = Yii::t('app', 'Modification type pièce identite : '.$model->code, [
    'name' => $model->code,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Type piece identite'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="row">
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?=Yii::t('app', 'Modifier un type de pièce d\'identité')?></h3>
            </div>
            <div class="box-body">
                <?= $this->render('_form', [
                    'model' => $model,
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
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-repeat']) . Yii::t('app', 'Actualiser'), ['index'], ['class' => 'btn btn-app'])?>
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-bullhorn'])
                    . Html::tag('span', '3', ['class' => 'badge bg-yellow'])
                    . Yii::t('app', 'Notifications'), ['index'], ['class' => 'btn btn-app'])?>
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-user']) . Yii::t('app', 'Mon profil'), ['index'], ['class' => 'btn btn-app'])?>
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-bullhorn'])
                    . Html::tag('span', '67', ['class' => 'badge bg-teal'])
                    . Yii::t('app', 'Demandes'), ['index'], ['class' => 'btn btn-app'])?>
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-envelope'])
                    . Html::tag('span', '67', ['class' => 'badge bg-aqua'])
                    . Yii::t('app', 'Messagerie'), ['index'], ['class' => 'btn btn-app'])?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>
