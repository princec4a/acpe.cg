<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\KitTypeAutorisation;
use common\models\KitEntreprise;
use common\models\KitEmploye;
use common\models\KitTypeAutorisationPiece;


/* @var $this yii\web\View */
/* @var $model common\models\KitDemande */
/* @var $form yii\widgets\ActiveForm */
/* @var $enterprise common\models\KitEntreprise */

$enterprise = KitEntreprise::findOne(['user_id' => Yii::$app->user->id]);

?>

<div class="kit-demande-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>

    <?= $form->field($model, 'employe_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(KitEmploye::getDataListEnterprise($enterprise->id), 'id', 'nom'),
        'theme' => Select2::THEME_DEFAULT,
        'options' => ['placeholder' => Yii::t('app','Selectionner')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <?= $form->field($model, 'type_autorisation_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(KitTypeAutorisation::getDataList(), 'id', 'libelle'),
        'theme' => Select2::THEME_DEFAULT,
        'options' => ['placeholder' => Yii::t('app','Selectionner')],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'pluginEvents' => [
            "change" => "function(e) {
                var typeAutorisation = $('#kitdemande-type_autorisation_id').val();
                //console.log(typeAutorisation);
                $.ajax({
                    type:'POST',
                    dataType:'json',
                    url: '".Yii::$app->getUrlManager()->createUrl('type-autorisation/get-piece-fournir-by-type-auto')."',
                    data: {
                        typeAutorisation: typeAutorisation,
                    },
                    success: function(data) {
                       console.log(data);
                       console.log(data.length);
                       var i = 0;
                        $('#kitElementDemande').empty();
                        while(i < data.length){
                            var label = data[i]['piece'] + ' (' +data[i]['nombre']+' exemplaire)';
                            addCheckbox(data[i]['id'], i, label, data[i]['ajoindre']);
                            i++;
                        }
                    }
                });
            }",
        ],
    ]);
    ?>

    <?php if(!$model->isNewRecord) { ?>
        <?php
        $array = [];
        foreach($model->kitElementDemandes as $element){
            $array[$element->piece_fournir_id]['id'] = $element->id;
            $array[$element->piece_fournir_id]['file_name'] = $element->file_name;
        }
        ?>
        <div id="kitElementDemande" class="form-group">
            <?php foreach(KitTypeAutorisationPiece::findOne($model->type_autorisation_id)->kitElementTypeAutorisations as $k=>$item) : ?>
                <div class="form-group">
                    <?php if(array_key_exists($item->piece_fournir_id, $array)){ ?>
                        <?=Html::checkbox('KitElementDemande['.$k.'][piece_fournir_id]',true, ['value' => $item->piece_fournir_id, 'id'=>'kitdemande-piece_fournir_id_'.$k]); ?>
                        <?=Html::label($item->pieceFournir->nom . ' ('.$item->nombre.' exemplaire)','kitdemande-piece_fournir_id_'.$k, ['value' => $item->piece_fournir_id, 'style' => 'position: relative; top: -3px; margin: 0 0 0 5px;']); ?>
                        <?=Html::fileInput('KitElementDemande[files][]', null, ['id'=>'kitdemande-file_'.$item->piece_fournir_id])?>
                        <?=Html::input('hidden','KitElementDemande['.$k.'][files_id]',$item->piece_fournir_id,['id'=>'files_id_'.$item->piece_fournir_id])?>
                        <?=Html::input('hidden','KitElementDemande['.$k.'][files_name]',null,['id'=>'files_name_'.$item->piece_fournir_id])?>
                        <?=Html::input('hidden','KitElementDemande['.$k.'][files]',null,['id'=>'files_'.$item->piece_fournir_id])?>
                    <?php } else {?>
                        <?=Html::checkbox('KitElementDemande['.$k.'][piece_fournir_id]',false, ['value' => $item->piece_fournir_id, 'id'=>'kitdemande-piece_fournir_id_'.$k]); ?>
                        <?=Html::label($item->pieceFournir->nom . ' ('.$item->nombre.' exemplaire)','kitdemande-piece_fournir_id_'.$k, ['value' => $item->piece_fournir_id, 'style' => 'position: relative; top: -3px; margin: 0 0 0 5px;']); ?>
                        <?=Html::fileInput('KitElementDemande[files][]', null, ['id'=>'kitdemande-file_'.$item->piece_fournir_id])?>
                        <?=Html::input('hidden','KitElementDemande['.$k.'][files_id]',null,['id'=>'files_id_'.$item->piece_fournir_id])?>
                        <?=Html::input('hidden','KitElementDemande['.$k.'][files_name]',null,['id'=>'files_name_'.$item->piece_fournir_id])?>
                        <?=Html::input('hidden','KitElementDemande['.$k.'][files]',null,['id'=>'files_'.$item->piece_fournir_id])?>
                    <?php } ?>
                </div>
            <?php endforeach;?>

        </div>
    <?php } else{ ?>
        <div id="kitElementDemande" class="form-group"></div>
    <?php } ?>

    <div class="row">
        <div class="col-xs-12">
            <?= Html::submitButton(Html::tag('i', '', ['class' => 'fa fa-fw fa-floppy-o']) .' '.Yii::t('app', 'Enregistrer'), ['class' => 'btn btn-success btn-block btn-flat']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    function addCheckbox(id,i,label,ajoindre) {
        var container = $('#kitElementDemande');
        var div = $('<div />').addClass("checkbox");
        var bloc = $('<div />').addClass("form-group");
        $('<input />', { type: 'checkbox', id: 'kitdemande-piece_fournir_id_'+i, value: id, name:'KitElementDemande['+i+'][piece_fournir_id]' }).appendTo(bloc);
        $('<label />', { 'for': 'kitdemande-piece_fournir_id_'+i, text: label, style : 'position: relative; top: -3px; margin: 0 0 0 5px;' }).appendTo(bloc);
        if(ajoindre){
            $('<input />', { type: 'file',
                id: 'kitdemande-file_'+id,
                name:'KitElementDemande[files][]'
            }).on('change', function(){
                var id = $(this).attr('id').split('_')[1];
                var fileName = $(this).val();
                $('#files_id_'+id).val(id);
                $('#files_name_'+id).val(fileName);
                $('#files_'+id).val(fileName);
            }).appendTo(bloc);
            $('<input />', { type: 'hidden', id: 'files_id_'+id, name:'KitElementDemande['+i+'][files_id]' }).appendTo(bloc);
            $('<input />', { type: 'hidden', id: 'files_name_'+id, name:'KitElementDemande['+i+'][files_name]' }).appendTo(bloc);
            $('<input />', { type: 'hidden', id: 'files_'+id, name:'KitElementDemande['+i+'][files]' }).appendTo(bloc);
        }
        //if(ajoindre) $('<input />', { type: 'file', id: 'kitdemande-file'+i, name:'KitElementDemande['+id+'][file][]' }).appendTo(bloc);
        //$('<input />', { type: 'text', id: 'Employe_Indemnites_Montant_'+id, value: montant, name:'Employe[Indemnite][Montant]['+id+']' }).appendTo(container);
        //$('<br />').appendTo(container);
        bloc.appendTo(container);
    }

    function onChangeFile(inputFile){
        var value = inputFile.value;
        alert(value);
    }
</script>

