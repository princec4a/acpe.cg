<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Priorite */

$this->title = Yii::t('app', 'Create Priorite');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Priorites'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="priorite-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
