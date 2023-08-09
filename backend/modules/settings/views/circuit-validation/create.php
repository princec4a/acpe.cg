<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KitCircuitValidation */

$this->title = Yii::t('app', 'Create Kit Circuit Validation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kit Circuit Validations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kit-circuit-validation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
