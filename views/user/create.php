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

$this->title = Yii::t('yiiplus/desktop', '创建用户');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', '用户列表'), 'url' => ['index']];
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
                <?= Html::submitButton(Yii::t('yiiplus/desktop', '提交'), ['class' => 'btn btn-primary', 'name' => 'submit-button']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="box-footer"></div>
</div>