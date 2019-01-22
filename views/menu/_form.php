<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiiplus\desktop\models\Menu;
use yii\helpers\Json;

$opts = Json::htmlEncode(['menus' => Menu::getMenuSource(), 'routes' => Menu::getSavedRoutes()]);
$this->registerJs("var _opts = $opts;");
$this->registerJs($this->render('_script.js'));
?>
<div class="menu-form">
<?php $form = ActiveForm::begin(); ?>
    <?= Html::activeHiddenInput($model, 'parent', ['id' => 'parent_id']); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>
            <?= $form->field($model, 'parent_name')->textInput(['id' => 'parent_name']) ?>
            <?= $form->field($model, 'route')->textInput(['id' => 'route']) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'order')->input('number') ?>
            <?= $form->field($model, 'data')->textarea(['rows' => 5]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('yiiplus/desktop', '提交'), ['class' => 'btn btn-primary', 'name' => 'submit-button']) ?>
    </div>
<?php ActiveForm::end(); ?>
</div>