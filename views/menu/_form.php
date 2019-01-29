<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Json;
use yiiplus\desktop\models\Menu;

$opts = Json::htmlEncode(['menus' => Menu::getMenuSource(), 'routes' => Menu::getSavedRoutes()]);
$this->registerJs("var _opts = $opts;");
$this->registerJs($this->render('_script.js'));
$options = Json::htmlEncode(['source' => Menu::getSavedRoutes()]);
$this->registerJs("$('#route').autocomplete($options);");
?>

<?php $form = ActiveForm::begin(); ?>
<div class="box-body">
    <?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>
    
    <?= $form->field($model, 'parent')->dropDownList($model::getDropDownList(\yiiplus\desktop\components\Tree::build($model::find()->asArray()->all(), 'id', 'parent', 'children', null)), ['encode' => false, 'prompt' => Yii::t('yiiplus/desktop', '请选择')]) ?>

    <?= $form->field($model, 'route')->textInput(['id' => 'route']) ?>

    <?= $form->field($model, 'icon')->widget(\yiiplus\desktop\widgets\iconpicker\IconPickerWidget::className()) ?>

    <?= $form->field($model, 'order')->input('number') ?>

    <?= $form->field($model, 'data')->textarea(['rows' => 4]) ?>
</div>
<div class="box-footer">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="btn-group pull-right">
            <?= Html::submitButton(Yii::t('yiiplus/desktop', '提交'), ['class' => 'btn btn-primary']) ?>
        </div>
        <div class="btn-group pull-left">
            <button type="reset" class="btn btn-warning">重置</button>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>