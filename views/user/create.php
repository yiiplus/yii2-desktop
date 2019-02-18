<?php
/**
 * yiiplus/yii2-desktop
 *
 * @category  PHP
 * @package   Yii2
 * @copyright 2018-2019 YiiPlus Ltd
 * @license   https://github.com/yiiplus/yii2-desktop/licence.txt Apache 2.0
 * @link      http://www.yiiplus.com
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \kartik\file\FileInput;
use \kartik\select2\Select2;

$this->title = Yii::t('yiiplus/desktop', '创建用户');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', '用户列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary user-create">
    <div class="box-header"></div>
    <div class="box-body">
        <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data', 'class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-sm-8\">{input}</div>\n<div class=\"col-sm-2\">{error}</div>",
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
            ],
        ]); ?>
        <?= Html::errorSummary($model) ?>
        <?= $form->field($model, 'username', [
            'inputTemplate' => '<div class="input-group"><span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>{input}</div>',
        ])?>

        <?= $form->field($model, 'nickname', [
            'inputTemplate' => '<div class="input-group"><span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>{input}</div>',
        ])?>
        <?= $form->field($model, 'avatar')->widget(FileInput::classname(), ['options' => ['multiple' => true], 'pluginOptions' => ['previewFileType' => 'image', 'initialPreviewAsData' => true, 'initialPreview' => [$model->avatar], 'dropZoneTitle' => '', 'maxFileCount' => 1, 'showRemove' => true, 'showUpload' => true,
            'showBrowse' => true,
            'showRemove' => true,
            'fileActionSettings' => [
                'showZoom' => true,
                'showUpload' => true,
                'showRemove' => true,
            ],]])?>
        <?= $form->field($model, 'email', [
            'inputTemplate' => '<div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>{input}</div>',
        ])->label('邮箱') ?>
        <?= $form->field($model, 'password', [
            'inputTemplate' => '<div class="input-group"><span class="input-group-addon"><i class="fa fa-eye-slash fa-fw"></i></span>{input}</div>',
        ])->passwordInput()?>
        <?= $form->field($model, 'repassword', [
            'inputTemplate' => '<div class="input-group"><span class="input-group-addon"><i class="fa fa-eye-slash fa-fw"></i></span>{input}</div>',
        ])->passwordInput() ?>
        <?=
        $form->field($model, 'role')->widget(Select2::classname(), [
            'options' => ['multiple' => true, 'placeholder' => 'Roles', 'id' => 'role'],
            'data' => $roles,
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>

        <?=
        $form->field($model, 'permission')->widget(Select2::classname(), [
            'initValueText' => ['帖子' => '帖子'],
            'options' => ['multiple' => true, 'placeholder' => 'Permissions', 'id' => 'permissions'],
            'data' => $permissions,
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>

    </div>
    <div class="box-footer">
        <div class="col-sm-2"></div>
        <div class="col-ms-8">
            <div class="btn-group pull-left">
                <?= Html::submitButton(Yii::t('yiiplus/desktop', 'Submit'), ['class' => 'btn btn-primary', 'name' => 'submit-button']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>