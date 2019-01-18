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
use yiiplus\desktop\modules\gii\GiiAsset;

$generators = Yii::$app->controller->module->generators;
$activeGenerator = Yii::$app->controller->generator;
$asset = GiiAsset::register($this);
?>
<?php $this->beginContent('@base/vendor/yiiplus/yii2-desktop/views/layouts/main.php'); ?>
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
                                'gii/view',
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