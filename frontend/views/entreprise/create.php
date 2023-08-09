<?php

use common\models\KitDemande;
use yii\helpers\ArrayHelper;
use common\models\KitLevel;
use common\models\KitEmploye;


/* @var $this yii\web\View */
/* @var $model common\models\KitEntreprise */

$this->title = Yii::t('app', 'Please enter your company information to access our services.');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Enterprises'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;

$url = \Yii::getAlias('@web/anonymous.png');
$levels = ArrayHelper::map(KitLevel::find()->all(), 'level_number','status_code');
$employes = ArrayHelper::map(KitEmploye::find()->where(['entreprise_id' => $model->id])->all(), 'id','id');
$reject = [];
foreach(KitLevel::find()->all() as $level) array_push($reject, $level->level_number * 10);
?>
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-albums-outline"></i></span>
            <div class="info-box-content">
                <span class="info-box-text"><?=Yii::t('app','MY REQUESTS')?></span>
                <span class="info-box-number"><?=KitDemande::find()->where(['employe_id' => $employes])->count()?></span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div><!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="ion-ios-checkmark-outline"></i></span>
            <div class="info-box-content">
                <span class="info-box-text"><?=Yii::t('app','VALIDATED REQUESTS')?></span>
                <span class="info-box-number"><?=KitDemande::find()->where(['statut'=>$levels, 'employe_id' => $employes])->count()?></span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div><!-- /.col -->


    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
            <div class="info-box-content">
                <span class="info-box-text"><?=Yii::t('app','OUTSTANDING REQUESTS')?></span>
                <span class="info-box-number"><?=KitDemande::find()->where(['statut'=>0, 'employe_id' => $employes])->count()?></span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div><!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="ion ion-ios-close-outline"></i></span>
            <div class="info-box-content">
                <span class="info-box-text"><?=Yii::t('app','REJECTED REQUESTS')?></span>
                <span class="info-box-number"><?=KitDemande::find()->where(['statut'=>$reject, 'employe_id' => $employes])->count()?></span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div><!-- /.col -->
</div><!-- /.row -->

<?= $this->render('_form', [
    'model' => $model,
    'modelsOptionValue' => $modelsOptionValue,
    'modelsTelephone' => $modelsTelephone,
    'url' => $url,
]) ?>

