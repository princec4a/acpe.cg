<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KitReport */

$this->title = Yii::t('app', 'Update Report: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="row">
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?=$this->title?></h3>
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
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-user']) . Yii::t('app', 'Mon profil'), ['index'], ['class' => 'btn btn-app'])?>

            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>
