<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KitValidationDemande */

$this->title = Yii::t('app', 'Create Kit Validation Demande');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kit Validation Demandes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kit-validation-demande-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
