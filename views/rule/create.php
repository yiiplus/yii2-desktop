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

$this->title = Yii::t('yiiplus/desktop', '创建规则');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', '规则列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary rule-create">
    <div class="box-header"></div>
    <div class="box-body">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
    <div class="box-footer"></div>
</div>