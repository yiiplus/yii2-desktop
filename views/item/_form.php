<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiiplus\desktop\components\RouteRule;
use yii\helpers\Json;
use yiiplus\desktop\components\Configs;

$context = $this->context;
$labels = $context->labels();
$rules = Configs::authManager()->getRules();
unset($rules[RouteRule::RULE_NAME]);
$source = Json::htmlEncode(array_keys($rules));

$js = <<<JS
    $('#rule_name').autocomplete({
        source: $source,
    });
JS;
$this->registerJs($js);

$opts = Json::htmlEncode([
    'items' => $model->getItems(),
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
?>

<div class="auth-item-form">
    <?php $form = ActiveForm::begin([
        'id' => 'item-form',
        'options' => ['enctype' => 'multipart/form-data', 'class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-8\">{input}</div>\n<div class=\"col-sm-2\">{error}</div>",
            'labelOptions' => ['class' => 'col-sm-2 control-label'],
        ],
    ]); ?>
    <div class="row">
        <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        <?= $form->field($model, 'ruleName')->textInput(['id' => 'rule_name']) ?>

        <?= $form->field($model, 'data')->textarea(['rows' => 6]) ?>


        <div class="col-sm-2"></div>
        <div class="col-sm-3">
            <input class="form-control search" data-target="available"
                   placeholder="<?= Yii::t('yiiplus/desktop', '搜索可用'); ?>">
            <select multiple size="20" class="form-control list" data-target="available"></select>
        </div>
        <div class="col-sm-2" align="center">
            <br><br><br><br>
            <?= Html::a('&gt;&gt;' . $animateIcon, ['assign', 'id' => $model->name], [
                'class' => 'btn btn-success btn-assign',
                'data-target' => 'available',
                'data-type' => $this->context->action->id == 'view' ? 'show' : 'assign',
                'title' => Yii::t('yiiplus/desktop', '分配'),
            ]); ?>
            <br><br>
            <?= Html::a('&lt;&lt;' . $animateIcon, ['remove', 'id' => $model->name], [
                'class' => 'btn btn-danger btn-assign',
                'data-target' => 'assigned',
                'data-type' => $this->context->action->id == 'view' ? 'show' : 'remove',
                'title' => Yii::t('yiiplus/desktop', '移除'),
            ]); ?>
        </div>
        <div class="col-sm-3">
            <input class="form-control search" data-target="assigned"
                   placeholder="<?= Yii::t('yiiplus/desktop', '搜索已分配'); ?>">
            <select multiple size="20" class="form-control list" data-target="assigned"></select>
        </div>
    </div>
    <div class="box-footer">
        <div></div>
        <div class="col-sm-2"></div>
        <div class="col-ms-8">
            <div class="btn-group pull-left">
                <?= Html::submitButton(Yii::t('yiiplus/desktop', 'Submit'), ['class' => 'btn btn-primary', 'name' => 'submit-button']) ?>
            </div>
        </div>

    </div>
    <?php ActiveForm::end(); ?>
</div>
