<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('yiiplus/desktop', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary user-create">
    <div class="box-header"></div>
    <div class="box-body">
        <div class="menu-form">
        <?php $form = ActiveForm::begin(); ?>
            <?= Html::errorSummary($model)?>
            <div class="row">
                <div class="col-lg-5">
                    <?= $form->field($model, 'username') ?>
                    <?= $form->field($model, 'email') ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('yiiplus/desktop', 'Submit'), ['class' => 'btn btn-primary', 'name' => 'submit-button']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="box-footer"></div>
</div>