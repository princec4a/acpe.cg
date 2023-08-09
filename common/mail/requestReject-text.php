<?php
use yii\helpers\Html;
use common\models\KitRejet;

/* @var $this yii\web\View */
/* @var $model common\models\KitDemande */


?>
<?= Yii::t('app','Hello').' '.  Html::encode($model->employe->nom) .' '. Html::encode($model->employe->prenom) ?>,

<?= Yii::t('app','We regret to inform you that ')?> <?=$model->typeAutorisation->libelle?> <?=Yii::t('app','was rejected this ')?> <?=date('d-m-Y',$model->signed_date)?> <?=Yii::t('app','for following reasons : ')?>

<?=KitRejet::find()->where(['demande_id'=>$model->id, 'level'=>$model->statut])->one()->motif;?>

<?= Yii::t('app','Please find attached the signed rejection letter')?>
