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

$context = $this->context;
$labels = $context->labels();
$this->title = Yii::t('yiiplus/desktop', '创建 ' . $labels['Item']);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary auth-item-create">
    <div class="box-header with-border"></div>
    <div class="box-body">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</div>