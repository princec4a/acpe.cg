<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\form\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use common\models\KitTypePieceIdentite;
use common\models\KitVille;
use budyaga\cropper\Widget;
use yii\helpers\Url;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\KitEntreprise */
/* @var $form yii\widgets\ActiveForm */


$js = '

jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    console.log("afterInsert");
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("Téléphone: " + (index + 1))
    });

});

jQuery(".dynamicform_wrapper").on("beforeInsert", function(e, item) {

});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {

});

';


$this->registerJs($js);
?>

<?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs pull-right">
        <li><a href="#tab_4-2" data-toggle="tab">Téléphone</a></li>
        <li><a href="#tab_3-2" data-toggle="tab">Identification</a></li>
        <li ><a href="#tab_2-2" data-toggle="tab"><?=Yii::t('app','Legal Representative')?></a></li>
        <li class="active"><a href="#tab_1-1" data-toggle="tab">Général</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1-1">
            <?= $form->field($model, 'raison_sociale')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'sigle')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'email')->textInput() ?>

            <?= $form->field($model, 'adresse_congo')->textarea(['rows' => 2]) ?>

            <?= $form->field($model, "ville_id")->dropDownList(
                ArrayHelper::map(KitVille::find()->all(), 'id', 'nom'),
                [
                    'prompt'=>'Selectionner ...',
                    'class' => 'form-control input-sm',
                    'onchange'=>'function()'
                ]
            ) ?>
            <?= $form->field($model, 'activite_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(\common\models\KitActivity::find()->all(), 'id', 'description'),
                'theme' => Select2::THEME_DEFAULT,
                'options' => ['placeholder' => Yii::t('app','Selectionner')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
            <?= $form->field($model, 'logo')->widget(Widget::className(), [
                'uploadUrl' => Url::toRoute('/user/user/uploadPhoto'),
            ]) ?>
        </div><!-- /.tab-pane -->
        <div class="tab-pane" id="tab_2-2">
            <?= $form->field($model, 'legal_representative_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'legal_representative_givename')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'fonction')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'telephone')->textInput() ?>

            <?= $form->field($model, 'sex')->dropDownList(
                [
                    'M'=>'Homme',
                    'F'=>'Femme',
                ],
                [
                    'prompt'=>'Selectionner ...',
                    'class' => 'form-control input-sm',
                    'onchange'=>'JS: var value = (this.value);
                                         console.log(value);'

                ]
            ) ?>
        </div><!-- /.tab-pane -->
        <div class="tab-pane" id="tab_3-2">
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
                    <i class="fa fa-file-text"></i><?=Yii::t('app','Document d\'identification')?>
                    <button type="button" class="pull-right add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Ajouter</button>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="form-options-body">
                <?php foreach ($modelsOptionValue as $index => $modelOptionValue): ?>
                    <div class="form-options-item">
                        <div class="panel-heading">
                            <button type="button" class="pull-right remove-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                            <div class="clearfix"></div>
                        </div>
                        <?php
                        // necessary for update action.
                        if (!$modelOptionValue->isNewRecord) {
                            echo Html::activeHiddenInput($modelOptionValue, "[{$index}]id");
                        }
                        ?>
                        <div class="col-xs-8"><?= $form->field($modelOptionValue, "[{$index}]type_piece_id")->dropDownList(
                                ArrayHelper::map(KitTypePieceIdentite::find()->all(), 'id', 'libelle'),
                                [
                                    'prompt'=>'Selectionner ...',
                                    'class' => 'form-control input-sm',
                                    'onchange'=>'function()'
                                ]
                            ) ?></div>

                        <div class="col-xs-4"><?= $form->field($modelOptionValue, "[{$index}]numero")->textInput(['class' => 'form-control input-sm']) ?></div>

                        <div class="col-xs-3">
                            <?= $form->field($modelOptionValue, "[{$index}]date_emission")->widget(\yii\widgets\MaskedInput::class, [
                                'mask' => '99-99-9999',
                            ]) ?>
                        </div>

                        <div class="col-xs-3">
                            <?= $form->field($modelOptionValue, "[{$index}]date_expiration")->widget(\yii\widgets\MaskedInput::class, [
                                'mask' => '99-99-9999',
                            ]) ?>
                        </div>



                        <div class="clearfix "></div>
                        <div class="box-footer" style="margin: 0; padding: 0;">&nbsp;</div>

                    </div>
                <?php endforeach; ?>

            </div>
            <?php DynamicFormWidget::end(); ?>
        </div><!-- /.tab-pane -->
        <div class="tab-pane" id="tab_4-2">
            <?= $form->field($model, 'contact_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'contact_poste')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'contact_sex')->dropDownList(
                [
                    'M'=>'Homme',
                    'F'=>'Femme',
                ],
                [
                    'prompt'=>'Selectionner ...',
                    'class' => 'form-control input-sm',
                    'onchange'=>'JS: var value = (this.value);
                                         console.log(value);'

                ]
            ) ?>

            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper',
                'widgetBody' => '.form-telephone-body',
                'widgetItem' => '.form-telephone-item',
                'min' => 1,
                'insertButton' => '.add-phone',
                'deleteButton' => '.remove-phone',
                'model' => $modelsTelephone[0],
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
                    <i class="fa fa-file-text"></i><?=Yii::t('app','Téléphone')?>
                    <button type="button" class="pull-right add-phone btn btn-success btn-xs"><i class="fa fa-plus"></i> Ajouter</button>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="form-telephone-body">
                <?php foreach ($modelsTelephone as $index => $modelOptionValue): ?>
                    <div class="form-telephone-item">
                        <div class="panel-heading">
                            <span class="panel-title-address">Téléphone: <?= ($index + 1) ?></span>
                            <button type="button" class="pull-right remove-phone btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                            <div class="clearfix"></div>
                        </div>
                        <?php
                        // necessary for update action.
                        if (!$modelOptionValue->isNewRecord) {
                            echo Html::activeHiddenInput($modelOptionValue, "[{$index}]id");
                        }
                        ?>

                        <div class="col-xs-4"><?= $form->field($modelOptionValue, "[{$index}]numero")->textInput(['class' => 'form-control input-sm']) ?></div>

                        <div class="clearfix "></div>
                        <div class="box-footer" style="margin: 0; padding: 0;">&nbsp;</div>

                    </div>
                <?php endforeach; ?>

            </div>
            <?php DynamicFormWidget::end(); ?>
        </div><!-- /.tab-pane -->
    </div><!-- /.tab-content -->
</div><!-- nav-tabs-custom -->

<div class="row">
    <div class="col-xs-12">
        <?= Html::submitButton(Html::tag('i', '', ['class' => 'fa fa-fw fa-floppy-o']) .' '.Yii::t('app', 'Enregistrer'), ['class' => 'btn btn-success btn-block btn-flat']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
