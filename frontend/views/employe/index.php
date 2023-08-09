<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\KitEmployeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Employes');
$this->params['breadcrumbs'][] = $this->title;
$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],
    //'id',
    [
        'attribute' => 'photo',
        'format' => 'html',
        'label' => 'Photo',
        'value' => function ($data) {
            $url = \Yii::getAlias('@web/uploads/user/photo/nophoto.png');
            return (empty($data['photo']))? Html::img($url, ['width' => '40px']) : Html::img($data['photo'], ['width' => '40px']);
        },
    ],
    'nom',
    //'nom_jeune_fille',
    'prenom',
    'date_naissance',
    //'lieu_naissance:ntext',
    //'nationalite:ntext',
    'email:ntext',
    /*[
        'attribute'=>Yii::t('app','Entreprise'),
        'value'=>'Entreprise.raison_sociale',
    ],*/
    [
        'attribute' => 'lieu_travail',
        'value' => function ($data) {
            return $data->lieuTravail->nom;
        },
    ],
    //'ville_travail',
    //'statut_medical',
    //'type_contrat_id',
    //'reference_contrat',
    'fonction',
    //'date_embauche',
    //'date_fin_contrat',
    //'create_at',
    //'update_at',

    [
        'class' => 'kartik\grid\ActionColumn',
        'deleteOptions' => ['label' => '<button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-trash" style="color: #DD4B39"></i></button>'],
        'viewOptions' => ['label' => '<button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-eye" style="color: #008D4C"></i></button>'],
        'updateOptions' => ['label' => '<button class="btn btn-default btn-sm checkbox-toggle"><i class="glyphicon glyphicon-pencil"></i></button>'],
    ],
];
?>
<div class="kit-employe-index">
    <p>
        <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-fw fa-plus']) . Yii::t('app', ' Ajouter un employé '), ['create'], ['class' => 'btn btn-success']) ?>
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
            <h3 class="box-title pull-right"><?=Yii::t('app','Liste des employés')?></h3>
        </div><!-- /.box-header -->
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'summary'=>'',
            'columns' => $gridColumns,
        ]); ?>
    </div>
    <?php Pjax::end(); ?>

</div>
