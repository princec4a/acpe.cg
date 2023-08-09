<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TypeCourrier */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="type-courrier-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'libelle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="row">
        <div class="col-xs-12">
            <?= Html::submitButton(Html::tag('i', '', ['class' => 'fa fa-fw fa-floppy-o']) .' '.Yii::t('app', 'Enregistrer'), ['class' => 'btn btn-success btn-block btn-flat']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
