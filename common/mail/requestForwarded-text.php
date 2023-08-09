<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KitDemande */


?>
<?= Yii::t('app','Hello').' '.  Html::encode($model->employe->nom) .' '. Html::encode($model->employe->prenom) ?>,

<?= Yii::t('app','Your file has been saved.') ?>

<?= Yii::t('app','However, please contact our departmental agency of {department} to physically deposit the documents required for effective processing.', ['department'=>$model->employe->villeTravail->departement->nom])?>

<?= Yii::t('app','NB:')?> <?=Yii::t('app','The treatment does not begin until after the physical deposit.')?>
