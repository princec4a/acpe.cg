<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use common\models\KitTypePieceIdentite;
use common\models\KitVille;
use common\models\KitDepartement;
use common\models\KitPays;
use common\models\KitEntreprise;
use kartik\select2\Select2;
//use yii\jui\DatePicker;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use budyaga\cropper\Widget;
use yii\helpers\Url;
use common\models\KitEmploye;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model common\models\KitEmploye */
/* @var $form yii\widgets\ActiveForm */

$js = '

jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    console.log("afterInsert");
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("'.Yii::t('app','Téléphone: ').': " + (index + 1))
    });
});

jQuery(".dynamicform_wrapper").on("beforeInsert", function(e, item) {
    console.log("beforeInsert");
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    console.log("afterDelete");
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("'.Yii::t('app','Téléphone: ').': " + (index + 1))
    });
});

';


$this->registerJs($js);
?>

<div class="kit-employe-form">

    <?php $form = ActiveForm::begin([
        'id' => 'dynamic-form',
        'tooltipStyleFeedback' => true,
    ]); ?>
    <p><?=$form->errorSummary($model);?></p>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right">
            <li><a href="#tab_4-2" data-toggle="tab"><?=Yii::t('app','Phones')?></a></li>
            <li><a href="#tab_3-2" data-toggle="tab"><?=Yii::t('app','Identification')?></a></li>
            <li><a href="#tab_2-2" data-toggle="tab"><?=Yii::t('app','Professional info.')?></a></li>
            <li class="active"><a href="#tab_1-1" data-toggle="tab"><?=Yii::t('app','Personal info.')?></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1-1">
                <?= $form->field($model, 'nom')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'prenom')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'sexe')->dropDownList(
                    [
                        'HOMME'=>Yii::t('app','Male'),
                        'FEMME'=>Yii::t('app','Female'),
                    ],
                    [
                        'prompt'=>Yii::t('app','... Select ...'),
                        'class' => 'form-control input-sm',
                        'onchange'=>'JS: var value = (this.value);
                                     console.log(value);
                                     var field = document.querySelector(".field-kitemploye-nom_jeune_fille");
                                     if(value === "HOMME") field.style.display = "none";
                                     else field.style.display = "block";'

                    ]
                ) ?>

                <?= $form->field($model, 'nom_jeune_fille')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'categorie')->dropDownList(KitEmploye::getCategoryArray()) ?>

                <?= $form->field($model, 'date_naissance')->widget(
                    DatePicker::className(),
                    [
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                        ]
                    ]
                );?>

                <?= $form->field($model, 'lieu_naissance')->textInput(['rows' => 6]) ?>

                <?= $form->field($model, 'nationalite')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(KitPays::find()->all(), 'id', 'nompays'),
                    'theme' => Select2::THEME_DEFAULT,
                    'options' => ['placeholder' => Yii::t('app','... Select ...')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>

                <?= $form->field($model, 'email')->textInput(['rows' => 6]) ?>

                <?= $form->field($model, 'photo')->widget(Widget::className(), [
                    'uploadUrl' => Url::toRoute('/user/user/uploadPhoto'),
                ]) ?>

            </div><!-- /.tab-pane -->
            <div class="tab-pane" id="tab_2-2">
                <?= $form->field($model, 'entreprise_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(KitEntreprise::find()->all(), 'id', 'raison_sociale'),
                    'theme' => Select2::THEME_DEFAULT,
                    'options' => ['placeholder' => '... '.Yii::t('app','Select').' ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>

                <?= $form->field($model, 'lieu_travail')->dropDownList(
                    ArrayHelper::map(KitDepartement::find()->all(), 'id', 'nom'),[
                    'id'=>'workplace-id',
                    'prompt' => '... '.Yii::t('app','Select').' ...',
                ]); ?>

                <?= $form->field($model, 'ville_travail')->widget(DepDrop::classname(), [
                    'type' => DepDrop::TYPE_SELECT2,
                    'pluginOptions'=>[
                        'depends'=>['workplace-id'],
                        'placeholder'=>'... '.Yii::t('app','Select').' ...',
                        'url'=>Url::to(['/settings/departement/city'])
                    ]
                ]); ?>

                <?= $form->field($model, 'statut_medical')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'type_contrat')->dropDownList(
                    [
                        'CDD'=>'Contrat à Durée Déterminée',
                        'CDI'=>'Contrat à Durée Indéterminée',
                        'STG'=>'Stagiaire',
                        'TMP'=>'Temporaire',
                    ],
                    [
                        'prompt'=>'Selectionner ...',
                        'class' => 'form-control input-sm',
                        'onchange'=>'JS: var value = (this.value);
                                     console.log(value);
                                     var field = document.querySelector(".field-kitemploye-date_fin_contrat");
                                     if(value === "CDI") field.style.display = "none";
                                     else field.style.display = "block";'
                    ]
                ) ?>
                <?= $form->field($model, 'reference_contrat')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'fonction')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'date_embauche')->widget(
                    DatePicker::className(),
                    [
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                        ]
                    ]
                );?>

                <?= $form->field($model, 'date_fin_contrat')->widget(
                    DatePicker::className(),
                    [
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                        ]
                    ]
                );?>

            </div>
            <div class="tab-pane" id="tab_3-2">
                <?php DynamicFormWidget::begin([
                    'widgetContainer' => 'dynamicform_wrapper',
                    'widgetBody' => '.form-options-body',
                    'widgetItem' => '.form-options-item',
                    'limit' => 2,
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
                                ) ?>
                            </div>

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
                <?php DynamicFormWidget::begin([
                    'widgetContainer' => 'dynamicform_inner',
                    'widgetBody' => '.form-options-body',
                    'widgetItem' => '.form-options-item',
                    'limit' => 2,
                    'min' => 1,
                    'insertButton' => '.add-phone',
                    'deleteButton' => '.remove-phone',
                    'model' => $modelsTelephone[0],
                    'formId' => 'dynamic-form',
                    'formFields' => [
                        'id',
                        'numero',
                    ],

                ]); ?>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <i class="fa fa-file-text"></i><?=Yii::t('app','Téléphone')?>
                        <button type="button" class="pull-right add-phone btn btn-success btn-xs"><i class="fa fa-plus"></i> Ajouter</button>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="form-options-body">
                    <?php foreach ($modelsTelephone as $index => $modelOptionValue): ?>
                        <div class="form-options-item">
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

                            <div class="col-xs-6"><?= $form->field($modelOptionValue, "[{$index}]numero")->textInput(['class' => 'form-control input-sm']) ?></div>

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

</div>
