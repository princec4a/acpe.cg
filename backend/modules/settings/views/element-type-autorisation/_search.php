<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\KitElementTypeAutorisationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kit-element-type-autorisation-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'piece_fournir_id') ?>

    <?= $form->field($model, 'type_autorisation_id') ?>

    <?= $form->field($model, 'nombre') ?>

    <?= $form->field($model, 'a_joindre') ?>

    <?php // echo $form->field($model, 'obligatoire') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <?php // echo $form->field($model, 'date_effective') ?>

    <?php // echo $form->field($model, 'date_fin') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
