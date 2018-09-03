<?php
/**
 * 用户登录
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Y+';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<div class="login-box">
    <div class="login-logo">
        <a href="javascript:void(0)"><b>YiiPlus</b> Desktop</a>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg"><?= Yii::t('yiiplus/desktop', 'loginBoxMsg') ?></p>
        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>
        <?= $form
            ->field($model, 'username', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

        <?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->label(Yii::t('yiiplus/desktop', 'rememberMe'))->checkbox() ?>
            </div>
            <div class="col-xs-4">
                <?= Html::submitButton(Yii::t('yiiplus/desktop', 'Sign In'), ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>