<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\KitHistoriqueTrajet */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kit-historique-trajet-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'demande_id')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'date_reception')->textInput() ?>

    <?= $form->field($model, 'dae_transmission')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <div class="row">
        <div class="col-xs-12">
            <?= Html::submitButton(Html::tag('i', '', ['class' => 'fa fa-fw fa-floppy-o']) .' '.Yii::t('app', 'Enregistrer'), ['class' => 'btn btn-success btn-block btn-flat']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
