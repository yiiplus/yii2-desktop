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
use yiiplus\desktop\modules\gii\GiiAsset;

$generators = Yii::$app->controller->module->generators;
$activeGenerator = Yii::$app->controller->generator;
$asset = GiiAsset::register($this);
?>
<?php $this->beginContent('@yiiplus/desktop/views/layouts/main.php'); ?>
<div class="row">
    <div class="col-md-3 col-sm-4">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">生成类型</h3>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked">
                    <?php
                        foreach ($generators as $id => $generator) {
                            echo Html::tag("li", Html::a(Html::encode($generator->getName()), [
                                'view',
                                'id' => $id
                            ]), [
                                'class' => $generator === $activeGenerator ? 'active' : ''
                            ]);
                        }
                    ?>
            </ul>
            </div>
        </div>
    </div>
    <div class="col-md-9 col-sm-8">
        <?= $content?>
    </div>
</div>
<?php $this->endContent(); ?>