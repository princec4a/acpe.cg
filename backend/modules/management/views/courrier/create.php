<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Courrier */

$this->title = Yii::t('app', 'Create Courrier');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Courriers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="courrier-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
