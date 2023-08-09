<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\KitElementDemande */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kit-element-demande-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'piece_fournir_id')->textInput() ?>

    <?= $form->field($model, 'fichier')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'demande_id')->textInput() ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'update_at')->textInput() ?>

    <div class="row">
        <div class="col-xs-12">
            <?= Html::submitButton(Html::tag('i', '', ['class' => 'fa fa-fw fa-floppy-o']) .' '.Yii::t('app', 'Enregistrer'), ['class' => 'btn btn-success btn-block btn-flat']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
