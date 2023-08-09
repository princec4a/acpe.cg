<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\KitTypeUo;
use common\models\KitUo;


/* @var $this yii\web\View */
/* @var $model common\models\KitUo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kit-uo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'libelle')->textInput(['maxlength' => true]) ?>

    <?php
        echo $form->field($model, 'type')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(KitTypeUo::find()->all(), 'id', 'libelle'),
            'theme' => Select2::THEME_DEFAULT,
            'options' => [
                'placeholder' => Yii::t('app','Selectionner un type d\'unité organisationnelle')
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>
    <?php
    echo $form->field($model, 'parent')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(KitUo::find()->all(), 'id', 'libelle'),
        'theme' => Select2::THEME_DEFAULT,
        'options' => ['placeholder' => Yii::t('app','Selectionner l\'U.O parent')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
    <div class="row">
        <div class="col-xs-12">
            <?= Html::submitButton(Html::tag('i', '', ['class' => 'fa fa-fw fa-floppy-o']) .' '.Yii::t('app', 'Enregistrer'), ['class' => 'btn btn-success btn-block btn-flat']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
