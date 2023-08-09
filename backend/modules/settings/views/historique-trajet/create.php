<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KitHistoriqueTrajet */

$this->title = Yii::t('app', 'Create Kit Historique Trajet');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kit Historique Trajets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kit-historique-trajet-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
