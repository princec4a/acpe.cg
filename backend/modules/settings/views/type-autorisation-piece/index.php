<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\KitTypeAutorisationPieceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Type Autorisation (élément constitutif du dossier)');
$this->params['breadcrumbs'][] = $this->title;
$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],

    [
        'attribute'=>'type_autorisation_id',
        'value'=>'typeAutorisation.libelle',
    ],

    [
        'class' => 'kartik\grid\ActionColumn',
        'deleteOptions' => ['label' => '<button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-trash" style="color: #DD4B39"></i></button>'],
        'viewOptions' => ['label' => '<button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-eye" style="color: #008D4C"></i></button>'],
        'updateOptions' => ['label' => '<button class="btn btn-default btn-sm checkbox-toggle"><i class="glyphicon glyphicon-pencil"></i></button>'],
    ],
];
?>
<div class="kit-type-autorisation-piece-index">
    <p>
        <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-fw fa-plus']) . Yii::t('app', ' Ajouter des éléments constitutif'), ['create'], ['class' => 'btn btn-success']) ?>
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
            <h3 class="box-title pull-right"><?=Yii::t('app','Type d\'autorisation (élément constitutif du dossier)')?></h3>
        </div><!-- /.box-header -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary'=>'',
        'columns' => $gridColumns,
    ]); ?>

    <?php Pjax::end(); ?>
    </div>
</div>
