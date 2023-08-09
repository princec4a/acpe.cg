<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KitElementDemande */

$this->title = Yii::t('app', 'Create Kit Element Demande');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kit Element Demandes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kit-element-demande-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
