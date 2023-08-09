<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

use common\models\KitLevel;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\KitTypeAutorisation */
/* @var $modelsOptionValue common\models\KitTypeAutorisation[] */
/* @var $form yii\widgets\ActiveForm */

$js = '

jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    console.log("afterInsert");
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("'.Yii::t('app','Level n°').': " + (index + 1))
    });
});

jQuery(".dynamicform_wrapper").on("beforeInsert", function(e, item) {

});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    console.log("afterDelete");
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("'.Yii::t('app','Level n°').': " + (index + 1))
    });
});

';


$this->registerJs($js);
?>

<div class="kit-type-autorisation-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'libelle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'duree_validite')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type_duree')->dropDownList(
        ['' => '', 'JOUR' => 'Jour(s)', 'SEMAINE' => 'Semaine', 'MOIS' => 'Mois', 'ANNEE'=>'Année(s)']
    ); ?>

    <?= $form->field($model, 'nationalite')->dropDownList(
        ['' => '', 'CONGOLAISE' => 'Congolaise', 'ETRANGERE' => 'Etrangère']
    ); ?>

    <?= $form->field($model, 'signataire')->textInput() ?>

    <?= $form->field($model, 'prix')->textInput() ?>

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.form-options-body',
        'widgetItem' => '.form-options-item',
        'min' => 1,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $modelsOptionValue[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'level_id',
        ],

    ]); ?>

    <div class="box box-primary">
        <div class="box-header with-border">
            <i class="fa fa-file-text"></i> <?=Yii::t('app','Validation levels')?>
            <button type="button" class="pull-right add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Ajouter</button>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="form-options-body">
        <?php foreach ($modelsOptionValue as $index => $modelOptionValue): ?>
            <div class="form-options-item">
                <div class="panel-heading">
                    <span class="panel-title-address"><?=Yii::t('app','Level n°')?>: <?= ($index + 1) ?></span>
                    <button type="button" class="pull-right remove-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                    <div class="clearfix"></div>
                </div>
                <?php
                // necessary for update action.
                if (!$modelOptionValue->isNewRecord) {
                    echo Html::activeHiddenInput($modelOptionValue, "[{$index}]id");
                }
                ?>
                <div class="col-xs-10"><?= $form->field($modelOptionValue, "[{$index}]level_id")->dropDownList(
                        ArrayHelper::map(KitLevel::find()->all(), 'id', 'level_name'),
                        [
                            'prompt'=>'Selectionner ...',
                            'class' => 'form-control input-sm',
                            'onchange'=>'validate_dropdown(this)',
                        ]
                    )->label(false)?></div>
                <div class="clearfix "></div>
                <div class="box-footer" style="margin: 0; padding: 0;">&nbsp;</div>

            </div>
        <?php endforeach; ?>

    </div>
    <?php DynamicFormWidget::end(); ?>

    <div class="clearfix "></div>
    <div class="row">
        <div class="col-xs-12">
            <?= Html::submitButton(Html::tag('i', '', ['class' => 'fa fa-fw fa-floppy-o']) .' '.Yii::t('app', 'Enregistrer'), ['class' => 'btn btn-success btn-block btn-flat']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    function validate_dropdown(select){
        console.log(select.value);
        console.log(select.id.options);

        Array.from(document.querySelector("#"+select.id).options).forEach(function(option_element) {
            let option_text = option_element.text;
            let option_value = option_element.value;
            let is_option_selected = option_element.selected;

            console.log('Option Text : ' + option_text);
            console.log('Option Value : ' + option_value);
            console.log('Option Selected : ' + (is_option_selected === true ? 'Yes' : 'No'));

            console.log("\n\r");
            if(is_option_selected){
                option_element.selected = 'selected';
            }


        });
    }

</script>
