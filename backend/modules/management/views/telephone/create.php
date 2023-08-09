<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KitTelephone */

$this->title = Yii::t('app', 'Create Kit Telephone');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kit Telephones'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kit-telephone-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
