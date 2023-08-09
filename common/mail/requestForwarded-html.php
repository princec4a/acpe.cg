<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KitDemande */

?>
<div class="verify-email">
    <p><?= Yii::t('app','Hello').' '.  Html::encode($model->employe->nom) .' '. Html::encode($model->employe->prenom) ?>,</p>

    <p><?= Yii::t('app','Your file has been saved.')?></p>

    <p><?= Yii::t('app','However, please contact our departmental agency of {department} to physically deposit the documents required for effective processing.', ['department'=>$model->employe->villeTravail->departement->nom])?></p>

    <p><strong><?= Yii::t('app','NB:')?></strong><br /><?=Yii::t('app','The treatment does not begin until after the physical deposit.')?></p>
</div>