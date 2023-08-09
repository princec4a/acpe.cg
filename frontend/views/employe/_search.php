<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\KitEmployeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kit-employe-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nom') ?>

    <?= $form->field($model, 'nom_jeune_fille') ?>

    <?= $form->field($model, 'prenom') ?>

    <?= $form->field($model, 'date_naissance') ?>

    <?php // echo $form->field($model, 'lieu_naissance') ?>

    <?php // echo $form->field($model, 'nationalite') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'lieu_travail') ?>

    <?php // echo $form->field($model, 'ville_travail') ?>

    <?php // echo $form->field($model, 'statut_medical') ?>

    <?php // echo $form->field($model, 'type_contrat') ?>

    <?php // echo $form->field($model, 'reference_contrat') ?>

    <?php // echo $form->field($model, 'fonction') ?>

    <?php // echo $form->field($model, 'date_embauche') ?>

    <?php // echo $form->field($model, 'date_fin_contrat') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
