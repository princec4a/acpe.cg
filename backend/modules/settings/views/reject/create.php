<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KitRejet */

$this->title = Yii::t('app', 'Create Kit Rejet');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kit Rejets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kit-rejet-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
