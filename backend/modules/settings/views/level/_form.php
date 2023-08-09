<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\KitLevel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kit-level-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'level_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'level_number')->textInput() ?>

    <?= $form->field($model, 'status_code')->textInput() ?>

    <?= $form->field($model, 'status_name')->textInput() ?>

    <div class="row">
        <div class="col-xs-12">
        <?= Html::submitButton(Html::tag('i', '', ['class' => 'fa fa-fw fa-floppy-o']) .' '.Yii::t('app', 'Enregistrer'), ['class' => 'btn btn-success btn-block btn-flat']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
