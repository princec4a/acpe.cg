<?php

/* @var $this yii\web\View */

use common\models\KitDemande;
use common\models\KitLevel;
use yii\helpers\ArrayHelper;
use common\models\KitTypeAutorisation;
use dosamigos\chartjs\ChartJs;
use common\models\KitEntreprise;
use yii\data\Pagination;
use yii\widgets\LinkPager;

$this->title = Yii::t('app','Dashboard');
$this->params['breadcrumbs'][] = $this->title;

$levels = ArrayHelper::map(KitLevel::find()->all(), 'level_number','status_code');
$reject = [];
foreach(KitLevel::find()->all() as $level) array_push($reject, $level->level_number * 10);
$typeAutolabels = $typeAutoData = array();

$data = array();

foreach(KitEntreprise::find()->orderBy('raison_sociale')->all() as $model){
    foreach(KitTypeAutorisation::find()->orderBy('code')->all() as $j => $item){
        $data[$model->raison_sociale][$item->code] = (count($model->kitEmployes) > 0)?
            KitDemande::find()->where(['employe_id'=>ArrayHelper::map($model->kitEmployes, 'id','id'), 'type_autorisation_id' => $item->id])->count()
            : 0;
    }
}

$pages = new Pagination(['totalCount' => count($data), 'pageSize'=>10]);

?>
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?=KitDemande::find()->count()?></h3>
                <p><?=Yii::t('app','All Resquets')?></p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer"><?=Yii::t('app','More info')?> <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?=KitDemande::find()->where(['statut'=>$levels])->count()?></h3>
                <p><?=Yii::t('app','VALIDATED REQUESTS')?></p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer"><?=Yii::t('app','More info')?> <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?=KitDemande::find()->where(['statut'=>0])->count()?></h3>
                <p><?=Yii::t('app','OUTSTANDING REQUESTS')?></p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer"><?=Yii::t('app','More info')?> <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3><?=KitDemande::find()->where(['statut'=>$reject])->count()?></h3>
                <p><?=Yii::t('app','REJECTED REQUESTS')?></p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer"><?=Yii::t('app','More info')?> <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div><!-- ./col -->
</div><!-- /.row -->
<div class="row">
    <div class="col-lg-6 col-xs-6">
        <div class="box box-info" style="min-height: 265px;">
            <div class="box-header with-border">
                <h3 class="box-title"><?=Yii::t('app','Total des actes encaissés')?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body" style="display: block;">
                <div class="table-responsive">
                    <table class="table no-margin">
                        <thead>
                        <tr>
                            <th><?=Yii::t('app','Actes')?></th>
                            <th><?=Yii::t('app','P.U')?></th>
                            <th><?=Yii::t('app','Nombre')?></th>
                            <th><?=Yii::t('app','Montant')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach(KitTypeAutorisation::find()->all() as $model) : ?>
                            <?php array_push($typeAutolabels, $model->libelle);?>
                            <?php
                            $amount = $price = 0;
                            foreach ($model->kitDemandes as $item) {
                                $amount = $amount + $item->prix;
                                $price = $item->prix;
                            } ?>
                            <?php if($amount > 0 ): ?>
                            <tr>
                                <td><?=$model->libelle?></td>
                                <td><span class="label label-warning" style="font-size: 12.5px;"><?=Yii::$app->formatter->asCurrency($price)?></span></td>
                                <td><span class="label label-info" style="font-size: 12.5px; background-color: #3c8dbc!important;"><?=count($model->kitDemandes)?></span></td>
                                <td>
                                    <?php array_push($typeAutoData, $amount) ?>
                                    <span class="label label-success" style="font-size: 12.5px"><?=Yii::$app->formatter->asCurrency($amount)?></span>
                                </td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div><!-- /.table-responsive -->
            </div><!-- /.box-body-->
        </div>
    </div>
    <div class="col-lg-6 col-xs-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><?=Yii::t('app','Progression de la vente des Actes')?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?= ChartJs::widget([
                        'type' => 'doughnut',
                        'id' => 'structurePie',
                        'options' => [
                            'height' => 200,
                            'width' => 600,
                        ],
                        'data' => [
                            'radius' =>  "90%",
                            'labels' => $typeAutolabels, // Your labels
                            'datasets' => [
                                [
                                    'data' => $typeAutoData, // Your dataset
                                    'label' => '',
                                    'backgroundColor' => [
                                        '#ADC3FF',
                                        '#FF9A9A',
                                        'rgba(190, 124, 145, 0.8)',
                                        'rgba(0, 166, 90, 0.8)',
                                        'rgba(0, 192, 239, 0.8)',
                                        'rgba(243, 156, 18, 0.8)',
                                        'rgba(221, 75, 57, 0.8)',
                                        'rgba(34, 45, 50, 0.8)',
                                        'rgba(60, 141, 188, 0.8)',
                                        'rgba(107, 217, 217, 0.8)',
                                        'rgba(207, 133, 15, 0.8)',
                                    ],
                                    'borderColor' =>  [
                                        '#fff',
                                        '#fff',
                                        '#fff'
                                    ],
                                    'borderWidth' => 1,
                                    'hoverBorderColor'=>["#999","#999","#999"],
                                ]
                            ]
                        ],
                        'clientOptions' => [
                            'legend' => [
                                'display' => false,
                                'position' => 'bottom',
                                'labels' => [
                                    'fontSize' => 14,
                                    'fontColor' => "#425062",
                                ]
                            ],
                            'tooltips' => [
                                'enabled' => true,
                                'intersect' => true
                            ],
                            'hover' => [
                                'mode' => false
                            ],
                            'maintainAspectRatio' => false,
                        ],
                    ])
                ?>
            </div><!-- /.box-body -->
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-xs-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><?=Yii::t('app','Total des actes par entreprise')?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body" style="display: block;">
                <div class="table-responsive">
                    <table class="table no-margin">
                        <thead>
                        <tr>
                            <th><?=Yii::t('app','Enterprises')?></th>
                            <?php foreach(KitTypeAutorisation::find()->orderBy('code')->all() as $model) : ?>
                            <th><?=$model->code?></th>
                            <?php endforeach; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($data as $key => $item) : ?>
                            <?php if(array_sum($item) > 0) : ?>
                            <tr>
                                <td><?=$key?></td>
                                <?php foreach($item as $row) : ?>
                                <td>
                                    <?=($row > 0)?
                                        '<span class="label label-success" style="font-size: 12.5px">'.$row.'</span>' :
                                        '<span class="label label-danger" style="font-size: 12.5px">'.$row.'</span>'
                                    ?>
                                </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div><!-- /.table-responsive -->
                <div class="box-footer clearfix">
                    <?php /* LinkPager::widget([
                            'pagination' => $pages,
                        ]);*/
                    ?>
                </div>
            </div><!-- /.box-body-->
        </div>
    </div>
</div>

<!-- Main row -->
<!--<div class="row">-->
<!--    <!-- Left col -->
<!--    <section class="col-lg-7 connectedSortable">-->
<!--        <!-- Custom tabs (Charts with tabs)-->
<!--        <div class="nav-tabs-custom">-->
<!--            <!-- Tabs within a box -->
<!--            <ul class="nav nav-tabs pull-right">-->
<!--                <li class="active"><a href="#revenue-chart" data-toggle="tab">Area</a></li>-->
<!--                <li><a href="#sales-chart" data-toggle="tab">Donut</a></li>-->
<!--                <li class="pull-left header"><i class="fa fa-inbox"></i> Sales</li>-->
<!--            </ul>-->
<!--            <div class="tab-content no-padding">-->
<!--                <!-- Morris chart - Sales -->
<!--                <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;"></div>-->
<!--                <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;"></div>-->
<!--            </div>-->
<!--        </div><!-- /.nav-tabs-custom -->
<!---->
<!--    </section><!-- /.Left col -->
<!--    <!-- right col (We are only adding the ID to make the widgets sortable)-->
<!--    <section class="col-lg-5 connectedSortable">-->
<!---->
<!--        <!-- Map box -->
<!--        <div class="box box-solid bg-light-blue-gradient">-->
<!--            <div class="box-header">-->
<!--                <!-- tools box -->
<!--                <div class="pull-right box-tools">-->
<!--                    <button class="btn btn-primary btn-sm daterange pull-right" data-toggle="tooltip" title="Date range"><i class="fa fa-calendar"></i></button>-->
<!--                    <button class="btn btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>-->
<!--                </div><!-- /. tools -->
<!---->
<!--                <i class="fa fa-map-marker"></i>-->
<!--                <h3 class="box-title">-->
<!--                    Visitors-->
<!--                </h3>-->
<!--            </div>-->
<!--            <div class="box-body">-->
<!--                <div id="world-map" style="height: 250px; width: 100%;"></div>-->
<!--            </div><!-- /.box-body-->
<!--            <div class="box-footer no-border">-->
<!--                <div class="row">-->
<!--                    <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">-->
<!--                        <div id="sparkline-1"></div>-->
<!--                        <div class="knob-label">Visitors</div>-->
<!--                    </div><!-- ./col -->
<!--                    <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">-->
<!--                        <div id="sparkline-2"></div>-->
<!--                        <div class="knob-label">Online</div>-->
<!--                    </div><!-- ./col -->
<!--                    <div class="col-xs-4 text-center">-->
<!--                        <div id="sparkline-3"></div>-->
<!--                        <div class="knob-label">Exists</div>-->
<!--                    </div><!-- ./col -->
<!--                </div><!-- /.row -->
<!--            </div>-->
<!--        </div>-->
<!--        <!-- /.box -->
<!---->
<!---->
<!--    </section><!-- right col -->
<!--</div><!-- /.row (main row) -->
