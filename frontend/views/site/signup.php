<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Url;

$this->title = Yii::t('app','register');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-body register-box" style="margin-top: 1%; margin-bottom: 1%; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-radius: 3px;">
    <div class="register-box-body">
        <p class="login-box-msg" style="font-size: 3.2rem; line-height: 1.25; font-weight: 600; color: rgba(0,0,0,0.9);"><?=Yii::t('app','Register a new membership')?></p>
        <?php $form = ActiveForm::begin(['id' => 'form-profile_2']); ?>
        <div class="form-group has-feedback">
            <?= $form->field($model, 'username')->textInput(['class'=>'form-control', 'placeholder'=>Yii::t('app','username')])->label(false); ?>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <?= $form->field($model, 'email')->textInput(['class'=>'form-control', 'placeholder'=>Yii::t('app','email')])->label(false); ?>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <?= $form->field($model, 'password')->passwordInput(['class'=>'form-control', 'placeholder'=>Yii::t('app','password')])->label(false); ?>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-12 form-group">
                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-12">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>
            </div><!-- /.col -->
        </div>
        <div class="row">
            <div class="col-xs-12 form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox"> <?=Yii::t('app','I accept the' )?> <a href="#"><?=Yii::t('app','conditions')?></a>
                    </label>
                </div>
            </div><!-- /.col -->
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
            <div class="col-xs-12 form-group">
                <?= Html::submitButton(Yii::t('app', 'register'), ['class' => 'btn btn-primary btn-block btn-flat', 'style'=>'border-radius: 5px', 'name' => 'signup-button']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 form-group">
                <a href="login" class="text-center"><?=Yii::t('app','I already have an account')?></a>
            </div>
        </div>

        <?php ActiveForm::end(); ?>


    </div><!-- /.form-box -->
</div><!-- /.register-box -->
