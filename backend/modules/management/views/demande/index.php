<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\KitLevel;
/* @var $this yii\web\View */
/* @var $searchModel common\models\KitDemandeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Demandes');
$this->params['breadcrumbs'][] = $this->title;
$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],
    'code',
    'date_reception',
    [
        'attribute'=>'employe_id',
        'value' => function($model, $key, $index){
            return $model->employe->nom.' '.$model->employe->prenom;
        },
    ],
    [
        'attribute'=>'Type d\'autorisation',
        'value' => function($model, $key, $index){
            return $model->typeAutorisation->libelle;
        },
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
    //'employe_id',
    //'autorisation_id',

    [
        'class' => 'kartik\grid\ActionColumn',
        'deleteOptions' => ['label' => '<button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-trash" style="color: #DD4B39"></i></button>'],
        'viewOptions' => ['label' => '<button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-eye" style="color: #008D4C"></i></button>'],
        'updateOptions' => ['label' => '<button class="btn btn-default btn-sm checkbox-toggle"><i class="glyphicon glyphicon-pencil"></i></button>'],
        'visibleButtons' => [
            'update' => function ($model) {
                return \Yii::$app->user->can('demandeUpdate', ['demande' => $model]);
            },
            'delete' => function ($model) {
                return \Yii::$app->user->can('demandeDelete', ['demande' => $model]);
            },
        ]
    ],
];
?>
<div class="kit-demande-index">
    <p>
        <?= (Yii::$app->user->can('demandeCreate')) ? Html::a(Html::tag('i', '', ['class' => 'fa fa-fw fa-plus']) . Yii::t('app', ' Ajouter une demande'), ['create'], ['class' => 'btn btn-success']) : ''?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="box">
        <div class="box-header with-border">
            <?= ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns,
                'container' => [
                    'class' => 'btn-group',
                ],
                'dropdownOptions' => [
                    'label' => '',
                    'class' => 'btn btn-outline-secondary'
                ]
            ]);?>
            <h3 class="box-title pull-right"><?=Yii::t('app','Liste des demandes')?></h3>
        </div><!-- /.box-header -->
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'summary' => '',
            'columns' => $gridColumns,
        ]); ?>
    </div>
    <?php Pjax::end(); ?>

</div>
