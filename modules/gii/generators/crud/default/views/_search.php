<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$username = Yii::$app->user->identity->username;
echo "<?php\n";
?>
/**
 * 慧诊
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    <?= $username ?> <<?= $username ?>@himoca.com>
 * @copyright 2017-2019 北京慧诊科技有限公司
 * @license   https://www.huizhen.com/licence.txt Licence
 * @link      http://www.huizhen.com
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="box box-primary">
    <div class="box-header">
        <h2 class="box-title"><?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>搜索</h2>
        <div class="box-tools"><button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" data-original-title="" title=""><i class="fa fa-minus"></i></button></div>
    </div>
    <div class="box-body">

        <?= "<?php " ?>$form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        ]); ?>

        <?php
        $count = 0;
        foreach ($generator->getColumnNames() as $attribute) {
            if (++$count < 6) {
                echo "    <?= " . $generator->generateActiveSearchField($attribute) . " ?>\n\n";
            } else {
                echo "    <?php // echo " . $generator->generateActiveSearchField($attribute) . " ?>\n\n";
            }
        }
        ?>
        <div class="form-group">
            <?= "<?= " ?>Html::submitButton(<?= $generator->generateString('Search') ?>, ['class' => 'btn btn-primary btn-flat']) ?>
            <?= "<?= " ?>Html::resetButton(<?= $generator->generateString('Reset') ?>, ['class' => 'btn btn-default btn-flat']) ?>
        </div>

        <?= "<?php " ?>ActiveForm::end(); ?>

    </div>
</div>
