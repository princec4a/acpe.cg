<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\KitReport;
use kartik\form\ActiveForm;
use common\models\KitLevel;
use kartik\editors\Summernote;


/* @var $this yii\web\View */
/* @var $model common\models\KitReport */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kit-report-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'header')->widget(Summernote::class, [
        'useKrajeePresets' => true,
    ]); ?>

    <?= $form->field($model, 'body')->widget(Summernote::class, [
        'useKrajeePresets' => true,
    ]); ?>

    <?= $form->field($model, 'footer')->widget(Summernote::class, [
        'useKrajeePresets' => true,
    ]); ?>

    <?= $form->field($model, 'action')->dropDownList(KitReport::getActionArray()); ?>

    <?= $form->field($model, 'level_id')->dropDownList(
        ArrayHelper::map(KitLevel::find()->all(), 'id', 'level_name'),
        [
            'prompt'=>'Selectionner ...',
            'class' => 'form-control input-sm',
            'onchange'=>'validate_dropdown(this)',
        ]
    ) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
