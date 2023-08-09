<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\KitReport;

/* @var $this yii\web\View */
/* @var $model common\models\KitReport */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-md-8">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><?=$this->title?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        //'id',
                        'name',
                        [
                            'attribute'=>'header',
                            'format' => 'raw',
                        ],
                        [
                            'attribute'=>'body',
                            'format' => 'raw',
                        ],
                        [
                            'attribute'=>'footer',
                            'format' => 'raw',
                        ],
                        [
                            'attribute'=>'action',
                            'value'=> KitReport::getActionALabel($model->action),
                            'format' => 'raw',
                        ],
                        [
                            'attribute'=>'level_id',
                            'value'=>$model->level->level_name,
                            'format' => 'raw',
                        ],
                        //'created_ad',
                        //'updated_at',
                        //'created_by',
                    ],
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
                <?=Html::a(Html::tag('i', '', ['class' => 'fa fa-save']) . Yii::t('app', 'Ajouter'), ['create'], ['class' => 'btn btn-app'])?>
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

