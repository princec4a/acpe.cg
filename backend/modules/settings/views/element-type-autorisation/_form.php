<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use common\models\KitPieceFournir;
use yii\jui\DatePicker;
use kartik\select2\Select2;
use common\models\KitTypeAutorisation;

/* @var $this yii\web\View */
/* @var $model common\models\KitElementTypeAutorisation */
/* @var $form yii\widgets\ActiveForm */

$js = '

jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    console.log("afterInsert");
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("Pièce: " + (index + 1))
    });
    var typeAutorisationId = jQuery("#kitelementtypeautorisation-type_autorisation").val();
    console.log(typeAutorisationId);
    jQuery("[id ^=kitelementtypeautorisation][id $=type_autorisation_id]").val(typeAutorisationId);

});

jQuery(".dynamicform_wrapper").on("beforeInsert", function(e, item) {
    var typeAutorisationId = jQuery("#kitelementtypeautorisation-type_autorisation").val();
    console.log(typeAutorisationId);
    //if(typeAutorisationId.trim() == \'\' ){
    //    alert("Veuillez d\'abord sélectionner le type de d\'autorisation SVP");
    //}
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    console.log("afterDelete");
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("Pièce: " + (index + 1))
    });
});

';


$this->registerJs($js);
?>

<?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
<?= $form->field($model, 'type_autorisation_id')->widget(Select2::classname(), [
    'data' => ArrayHelper::map(KitTypeAutorisation::getDataList(), 'id', 'libelle'),
    'theme' => Select2::THEME_DEFAULT,
    'options' => [
        'placeholder' => Yii::t('app','Selectionner le type d\'autorisation'),
        'class' => 'form-control input-sm',
    ],
    'pluginOptions' => [
        'allowClear' => true
    ],
    'pluginEvents' => [
        "change" => "function(e) {
            var typePieceId = $('#kitelementtypeautorisation-type_autorisation_id').val();
            console.log(typePieceId);
            $('[id ^=kitelementtypeautorisation][id $=type_autorisation_id]').val(typePieceId);
        }",
    ],
]);
?>

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
        'piece_fournir_id',
        'nombre',
        'a_joindre',
        'obligatoire',
        'date_effective',
        'date_fin',
    ],

]); ?>

<div class="box box-primary">
    <div class="box-header with-border">
        <i class="fa fa-file-text"></i> Pièce à fournir
        <button type="button" class="pull-right add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Ajouter</button>
        <div class="clearfix"></div>
    </div>
</div>
<div class="form-options-body">
    <?php foreach ($modelsOptionValue as $index => $modelOptionValue): ?>
    <div class="form-options-item">
        <div class="panel-heading">
            <span class="panel-title-address">Pièce: <?= ($index + 1) ?></span>
            <button type="button" class="pull-right remove-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
            <div class="clearfix"></div>
        </div>
        <?php
        // necessary for update action.
        if (!$modelOptionValue->isNewRecord) {
            echo Html::activeHiddenInput($modelOptionValue, "[{$index}]id");
        }
            echo Html::activeHiddenInput($modelOptionValue, "[{$index}]type_autorisation_id");
        ?>
        <?php //$form->field($modelOptionValue, "[{$index}]type_autorisation_id")->textInput() ?>
        <div class="col-xs-10"><?= $form->field($modelOptionValue, "[{$index}]piece_fournir_id")->dropDownList(
                ArrayHelper::map(KitPieceFournir::find()->all(), 'id', 'nom'),
                            [
                                'prompt'=>'Selectionner ...',
                                'class' => 'form-control input-sm',
                                'onchange'=>'function()'
                            ]
            ) ?></div>

        <div class="col-xs-2"><?= $form->field($modelOptionValue, "[{$index}]nombre")->textInput(['class' => 'form-control input-sm']) ?></div>
        <?php /*
        <div class="col-xs-6">
            <?= $form->field($model,"[{$index}]date_effective")->widget(DatePicker::className(),
                [   'dateFormat' => 'dd/MM/yyyy',
                    'clientOptions' => [
                        'changeYear' => true,
                        'changeMonth' => true,
                        //'defaultDate' => '2014-01-01',
                        //'altFormat' => 'yy-mm-dd',
                    ]
                ]) ;?>
        </div>

        <div class="col-xs-4">
            <?= $form->field($model,"[{$index}]date_fin")->widget(DatePicker::className(),
                [   'dateFormat' => 'dd/MM/yyy',
                    'language' => 'fr',
                    'clientOptions' => [
                        'changeYear' => true,
                        'changeMonth' => true,
                        'defaultDate' => '2014-01-01',
                        //'altFormat' => 'yy-mm-dd',
                    ]
                ]); ?>
        </div>
        */ ?>
        <div class="col-xs-3"><?= $form->field($modelOptionValue, "[{$index}]a_joindre")->checkbox() ?></div>

        <div class="col-xs-3"><?= $form->field($modelOptionValue, "[{$index}]obligatoire")->checkbox() ?></div>



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
