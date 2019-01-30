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
use yii\widgets\DetailView;

$this->title = Yii::t('yiiplus/desktop', '日志管理');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', '日志管理'), 'url' => ['index']];
?>

<div class="box box-primary desktop-log-view">
    <div class="box-header with-border"></div>
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'route',
            'description:ntext',
            'created_at:datetime',
            'user_id',
            'ip',
        ],
    ]) ?>
    </div>
    <div class="box-footer"></div>
</div>
