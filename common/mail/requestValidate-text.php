<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KitDemande */


?>
<?= Yii::t('app','Hello').' '.  Html::encode($model->employe->nom) .' '. Html::encode($model->employe->prenom) ?>,

<?= Yii::t('app','We are pleased to send you your')?> <?=$model->typeAutorisation->libelle?> <?=Yii::t('app','as an attachment, and we thank you for your trust.')?>

<?= Yii::t('app','However, your')?> <?=$model->typeAutorisation->libelle?> <?=Yii::t('app','was signed on')?> <?=date('d-m-Y',$model->signed_date)?> <?=Yii::t('app','and will expire on')?> <?=date('d-m-Y',$model->expiration_date)?>