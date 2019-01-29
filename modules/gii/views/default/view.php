<?php
/**
 * 慧诊
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    zhouyang <zhouyang@himoca.com>
 * @copyright 2017-2019 北京慧诊科技有限公司
 * @license   https://www.huizhen.com/licence.txt Licence
 * @link      http://www.huizhen.com
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\gii\components\ActiveField;
use yii\gii\CodeFile;

$this->title = $generator->getName();
$templates = [];
//获取生成代码model模板
foreach ($generator->templates as $name => $path) {
    $templates[$name] = "$name ($path)";
}
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">
            <?= Html::encode($this->title) ?>
        </h3>
    </div>
    <div class="box-body default-view">
        <p><?= $generator->getDescription() ?></p>
        <?php
            $form = ActiveForm::begin([
                    'id' => "$id-generator",
                    'successCssClass' => '',
                    'fieldConfig' => [
                        'class' => ActiveField::className()
                    ]
            ]);
        ?>
        <?= $this->renderFile($generator->formView(), ['generator' => $generator,'form' => $form])?>
        <?= $form->field($generator, 'template')->sticky()->label('Code Template')->dropDownList($templates)->hint('
            Please select which set of the templates should be used to generated the code.
        ')?>
        <div class="form-group">
            <?= Html::submitButton('Preview', ['name' => 'preview', 'class' => 'btn btn-primary'])?>

            <?php if (isset($files)): ?>
            <?= Html::submitButton('Generate', ['name' => 'generate', 'class' => 'btn btn-success'])?>
            <?php endif; ?>
        </div>

        <?php
        if (isset($results)) {
            echo $this->render('view/results', [
                'generator' => $generator,
                'results' => $results,
                'hasError' => $hasError
            ]);
        } elseif (isset($files)) {
            echo $this->render('view/files', [
                'id' => $id,
                'generator' => $generator,
                'files' => $files,
                'answers' => $answers
            ]);
        }
        ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
