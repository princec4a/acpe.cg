<?php
use yii\helpers\Html;
use common\models\KitRejet;

/* @var $this yii\web\View */
/* @var $model common\models\KitDemande */

?>
<div class="verify-email">
    <p><?= Yii::t('app','Hello').' '.  Html::encode($model->employe->nom)  .' '. Html::encode($model->employe->prenom) ?>,</p>

    <p><?= Yii::t('app','We regret to inform you that ')?>&nbsp;<strong><?=$model->typeAutorisation->libelle?></strong>&nbsp;<?=Yii::t('app','was rejected this ')?>&nbsp;<strong><?=date('d-m-Y',$model->signed_date)?></strong>,&nbsp;<?=Yii::t('app','for following reasons : ')?></p>

    <p><strong><?=KitRejet::find()->where(['demande_id'=>$model->id, 'level'=>$model->statut])->one()->motif;?></strong></p>

    <p><?= Yii::t('app','Please find attached the signed rejection letter')?></p>
</div>