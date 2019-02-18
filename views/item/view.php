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
use yii\helpers\Json;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

$context = $this->context;
$labels = $context->labels();
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$opts = Json::htmlEncode([
    'items' => $model->getItems(),
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
?>

<div class="box box-primary auth-item-view">
    <div class="box-header with-border"></div>
    <div class="box-body">
        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'name',
                'description:ntext',
                'ruleName',
                'data:ntext',
            ],
            'template' => '<tr><th style="width:25%">{label}</th><td>{value}</td></tr>',
        ]);
        ?>
        <br>
        <div class="row">
            <div class="col-sm-5">
                <input class="form-control search" data-target="available"
                       placeholder="<?=Yii::t('yiiplus/desktop', '搜索可用');?>">
                <select multiple size="20" class="form-control list" data-target="available"></select>
            </div>
            <div class="col-sm-2" align="center">
                <br><br><br><br>
                <?=Html::a('&gt;&gt;' . $animateIcon, ['assign', 'id' => $model->name], [
                    'class' => 'btn btn-success btn-assign',
                    'data-target' => 'available',
                    'data-type' => $this->context->action->id == 'view' ? 0 : 1,
                    'title' => Yii::t('yiiplus/desktop', '分配'),
                ]);?>
                <br><br>
                <?=Html::a('&lt;&lt;' . $animateIcon, ['remove', 'id' => $model->name], [
                    'class' => 'btn btn-danger btn-assign',
                    'data-target' => 'assigned',
                    'data-type' => $this->context->action->id == 'view' ? 0 : 2,
                    'title' => Yii::t('yiiplus/desktop', '移除'),
                ]);?>
            </div>
            <div class="col-sm-5">
                <input class="form-control search" data-target="assigned"
                       placeholder="<?=Yii::t('yiiplus/desktop', '搜索已分配');?>">
                <select multiple size="20" class="form-control list" data-target="assigned"></select>
            </div>
        </div>
    </div>
    <div class="box-footer"></div>
</div>
