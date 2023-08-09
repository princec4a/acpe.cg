<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\KitElementTypeAutorisation */

$this->title = Yii::t('app', 'Update Kit Element Type Autorisation: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kit Element Type Autorisations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="kit-element-type-autorisation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
