<?php

use yii\helpers\Html;
use common\models\KitDemande;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\KitAcknowledgment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kit-acknowledgment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'demande_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(KitDemande::find()->where(['statut' => 0])->all(), 'id', 'code'),
        'theme' => Select2::THEME_DEFAULT,
        'options' => ['placeholder' => Yii::t('app','Selectionner')],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'pluginEvents' => [
            "change" => "function(e) {
                var demande = $('#kitacknowledgment-demande_id').val();
                console.log(demande);
                $.ajax({
                    type:'POST',
                    dataType:'json',
                    url: '".Yii::$app->getUrlManager()->createUrl('settings/type-autorisation/get-piece-by-demande')."',
                    data: {
                        demande: demande,
                    },
                    success: function(data) {
                       //console.log(data);
                       //console.log(data.length);
                       var i = 0;
                        $('#kitElementDemande').empty();
                        while(i < data.length){
                           if(data[i]['piece'] != ''){
                                var label = data[i]['piece'] + ' (' +data[i]['nombre']+' exemplaire)';
                                addCheckbox(data[i]['id'], i, label, data[i]['ajoindre']);
                           }
                           var name = data[i]['name'];
                           var autorisation = data[i]['autorisation'];
                           i++;
                        }
                        $('#name').val(name);
                        $('#name').show();
                        $('#autorisation').val(autorisation);
                        $('#autorisation').show();
                    }
                });
            }",
        ],
    ]);
    ?>

    <div class="form-group">
        <?= Html::input('text','','', $options=['class'=>'form-control', 'id'=>'name', 'style'=>'display:none']) ?>
    </div>
    <div class="form-group">
        <?= Html::input('text','','', $options=['class'=>'form-control', 'id'=>'autorisation', 'style'=>'display:none']) ?>
    </div>


    <div id="kitElementDemande" class="form-group"></div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    function addCheckbox(id,i,label,ajoindre) {
        var container = $('#kitElementDemande');
        var div = $('<div />').addClass("checkbox");
        var bloc = $('<div />').addClass("form-group");
        $('<input />', { type: 'checkbox', id: 'kitdemande-piece_fournir_id_'+i, value: id, name:'KitElementDemande['+i+'][piece_fournir_id]', style : 'float:left; margin: 3px 5px 0 0;' }).appendTo(bloc);
        $('<label />', { 'for': 'kitdemande-piece_fournir_id_'+i, text: label, style : 'float:left; width: 75%;' }).appendTo(bloc);
        $('<input />', { type: 'text', id: 'kitdemande-nombre_'+i, value: '', name:'KitElementDemande['+i+'][nombre]', style : 'float:left; margin: 0 0 10px 0;' }).appendTo(bloc);
        bloc.appendTo(container);
    }

    function onChangeFile(inputFile){
        var value = inputFile.value;
        alert(value);
    }
</script>
