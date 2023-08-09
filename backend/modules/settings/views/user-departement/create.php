<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KitUserDepartement */

$this->title = Yii::t('app', 'Create Kit User Departement');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kit User Departements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kit-user-departement-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
