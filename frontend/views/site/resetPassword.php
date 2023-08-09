<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app','Reset password');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="login-box" style="margin-top: 1%; margin-bottom: 1%; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-radius: 3px;">
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg" style="font-size: 2.2rem; line-height: 1.25; font-weight: 600; color: rgba(0,0,0,0.9);"><?= Html::encode($this->title) ?></p>
        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
        <?= $form->field($model, 'password')->passwordInput(['class'=>'form-control','style'=>'border-radius: 5px;', 'placeholder'=>Yii::t('app','password')])->label(false) ?>

        <div style="color:#999;margin:1em 0">
            <?= Html::a(Yii::t('app','Signup'), ['site/signup']) ?>
        </div>

        <div style="color:#999;margin:1em 0">
            <?= Html::a(Yii::t('app','I already have an account'), ['site/login']) ?>
        </div>

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->

