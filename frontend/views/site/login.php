<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Login';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-box" style="margin-top: 1%; margin-bottom: 1%; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-radius: 3px;">
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg" style="font-size: 3.2rem; line-height: 1.25; font-weight: 600; color: rgba(0,0,0,0.9);"><?= Yii::t('app', 'AUTHORISATION')?></p>

        <?php $form = ActiveForm::begin(['id' => 'login-widget-form']); ?>
        <?= $form->field($model, 'username')->textInput(['class'=>'form-control' , 'style'=>'border-radius: 5px;', 'placeholder'=>Yii::t('app','username')])->label(false); ?>
        <?= $form->field($model, 'password')->passwordInput(['class'=>'form-control','style'=>'border-radius: 5px;', 'placeholder'=>Yii::t('app','password')])->label(false) ?>
        <div class="row">
            <div class="col-xs-6">
                <div style="margin-top: 10px; margin-bottom: 10px;">
                    <?= Html::a(Yii::t('app','Forgot your password ?'), ['site/request-password-reset']) ?>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-xs-6">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
            <!-- /.col -->
        </div>
        <div class="row">
            <div class="col-xs-12">
                <p style="text-align: center;">
                    <?php $i=0;?>
                    <?php foreach(Yii::$app->params['languages'] as $key => $language) : ?>
                        <a href="<?=Url::to(['/site/language', 'lang'=> $key])?>" id ="<?= $key;?>" style="font-size: 15px; padding: 0 5px; margin: 0 5px;"><?=Yii::t('app',$language);?></a>
                        <?php if($key == $i) : ?>
                            |
                        <?php endif; ?>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= Html::submitButton(Yii::t('app', 'Login'),
                    ['class' => 'btn btn-primary btn-block btn-flat',
                        'name' => 'login-button',
                        'style' =>'border-radius: 5px;'
                    ]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>

        <div style="color:#999;margin:1em 0">
            <?= Html::a(Yii::t('app','Resend as new verification email?'), ['site/resend-verification-email']) ?>
        </div>

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
<div style="text-align: center;font-size: 1.6rem;">
    <?=Yii::t('app','Are you just starting out on our platform?')?> <?=Html::a(Yii::t('app','Signup'), Url::to('signup'), ['style'=>'color: #0078BD;'])?>
</div>
