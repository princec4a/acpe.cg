<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\KitCircuitValidationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Circuits de validation');
$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],

    //'id',
    'autorization_type_id',
    'level_id',
    'created_at',
    'updated_at',
    //'created_by',

    [
        'class' => 'kartik\grid\ActionColumn',
        'deleteOptions' => ['label' => '<button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-trash" style="color: #DD4B39"></i></button>'],
        'viewOptions' => ['label' => '<button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-eye" style="color: #008D4C"></i></button>'],
        'updateOptions' => ['label' => '<button class="btn btn-default btn-sm checkbox-toggle"><i class="glyphicon glyphicon-pencil"></i></button>'],
    ],
];
?>
<div class="kit-circuit-validation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-fw fa-plus']) . Yii::t('app', 'Ajouter un circuit de validation'), ['create'], ['class' => 'btn btn-success']) ?>
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
            <h3 class="box-title pull-right"><?=Yii::t('app','Circuits de validation')?></h3>
        </div><!-- /.box-header -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
    ]); ?>
    </div>
    <?php Pjax::end(); ?>

</div>
