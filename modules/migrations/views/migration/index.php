<?php

use yii\helpers\Html;
use yiiplus\desktop\modules\migrations\assets\MigrationAsset;
use yiiplus\desktop\modules\migrations\models\MigrationUtility;
use yiiplus\desktop\modules\migrations\models\ActiveForm;

MigrationAsset::register($this);
$this->title = Yii::t('yiiplus/desktop', '迁移');
?>
<div class="box box-primary">
    <div class="box-body">
<?php $form = ActiveForm::begin(['id' => 'form-search', 'method' => 'get', 'action' => ['index']]); ?>
    <?= $form->field($model, 'database')->dropDownList(MigrationUtility::getDatabases(), ['id'=>"form-controls",'options' => [$database => ['Selected'=>true]]]);?>
<?php ActiveForm::end()?>

<?php $form = ActiveForm::begin(['id' => 'form-submit']); ?>
        <?= $form->field($model, 'database')->textInput()->hiddenInput(['value'=>$database])->label(false); ?>
        <?= $form->field($model, 'migrationName')->textInput()?>
        <?= $form->field($model, 'migrationPath')->textInput()?>
        <?= $form->field($model, 'tableOption')->textInput()?>
    </div>
</div>

<?= $form->boxField($model, "tableSchemas")->checkboxList(MigrationUtility::getTableNames($database))->header(Yii::t('yiiplus/desktop', "迁移表结构"))->hint(Html::a(Yii::t('yiiplus/desktop', "全选"),'javascript:void(0)',['class'=>"select-all"]))?>
<?= $form->boxField($model, "tableDatas")->checkboxList(MigrationUtility::getTableNames($database))->header(Yii::t('yiiplus/desktop', "迁移表数据"))->hint(Html::a(Yii::t('yiiplus/desktop', "全选"),'javascript:void(0)',['class'=>"select-all"]))?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('yiiplus/desktop', '生成迁移文件'), ['class' => 'btn bg-maroon btn-flat btn-block ', 'name' => 'button-submit', 'id' => 'button-submit'])?>
</div>
<?php ActiveForm::end()?>
