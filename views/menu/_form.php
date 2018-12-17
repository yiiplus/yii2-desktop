<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Json;

use yiiplus\desktop\models\Menu;
use yiiplus\desktop\DesktopAsset;

$opts = Json::htmlEncode(['menus' => Menu::getMenuSource(), 'routes' => Menu::getSavedRoutes()]);
$this->registerJs("var _opts = $opts;");
$this->registerJs($this->render('_script.js'));
?>
<div class="box box-primary">
    <div class="box-body">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>
        
        <?= $form->field($model, 'parent')->dropDownList($model::getDropDownList(\yiiplus\desktop\components\Tree::build($model::find()->asArray()->all(), 'id', 'parent', 'children', null)), ['encode' => false, 'prompt' => '请选择']) ?>

        <?= $form->field($model, 'route')->textInput(['id' => 'route']) ?>

        <?= $form->field($model, 'icon')->widget(\yiiplus\desktop\widgets\iconpicker\IconPickerWidget::className()) ?>

        <?= $form->field($model, 'order')->input('number') ?>

        <?= $form->field($model, 'data')->textarea(['rows' => 4]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('yiiplus/desktop', 'Create') : Yii::t('yiiplus/desktop', 'Update'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
DesktopAsset::register($this);

$options = Json::htmlEncode([
    'source' => Menu::getSavedRoutes(),
]);
$this->registerJs("$('#route').autocomplete($options);");