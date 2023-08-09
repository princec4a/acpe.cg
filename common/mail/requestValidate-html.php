<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KitDemande */

?>
<div class="verify-email">
    <p><?= Yii::t('app','Hello').' '.  Html::encode($model->employe->nom) .' '. Html::encode($model->employe->prenom) ?>,</p>

    <p><?= Yii::t('app','We are pleased to send you your')?>&nbsp;<strong><?=$model->typeAutorisation->libelle?></strong>&nbsp;<?=Yii::t('app','as an attachment, and we thank you for your trust.')?></p>

    <p><?= Yii::t('app','However, your')?>&nbsp;<strong><?=$model->typeAutorisation->libelle?></strong>&nbsp;<?=Yii::t('app','was signed on')?>&nbsp;<strong><?=date('d-m-Y',$model->signed_date)?></strong>&nbsp;<?=Yii::t('app','and will expire on')?>&nbsp;<strong><?=date('d-m-Y', $model->expiration_date)?></strong></p>
</div>