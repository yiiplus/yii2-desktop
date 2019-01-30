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

$this->title = Yii::t('yiiplus/desktop', '路由列表');
$this->params['breadcrumbs'][] = $this->title;

$opts = Json::htmlEncode([
    'routes' => $routes,
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
?>

<div class="box box-primary">
    <div class="box-header with-border"></div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="input-group">
                    <input id="inp-route" type="text" class="form-control"
                           placeholder="<?=Yii::t('yiiplus/desktop', '新建路由');?>">
                    <span class="input-group-btn">
                        <?=Html::a(Yii::t('yiiplus/desktop', '新建') . $animateIcon, ['create'], [
            'class' => 'btn btn-success',
            'id' => 'btn-new',
        ]);?>
                    </span>
                </div>
            </div>
        </div>
        <p>&nbsp;</p>
        <div class="row">
            <div class="col-sm-5">
                <div class="input-group">
                    <input class="form-control search" data-target="available"
                           placeholder="<?=Yii::t('yiiplus/desktop', '搜索可用的路由');?>">
                    <span class="input-group-btn">
                        <?=Html::a('<span class="glyphicon glyphicon-refresh"></span>', ['refresh'], [
                            'class' => 'btn btn-default',
                            'id' => 'btn-refresh',
                        ]);?>
                    </span>
                </div>
                <select multiple size="20" class="form-control list" data-target="available"></select>
            </div>
            <div class="col-sm-2" align="center">
                <br><br><br><br>
                <?=Html::a('&gt;&gt;' . $animateIcon, ['assign'], [
                    'class' => 'btn btn-success btn-assign',
                    'data-target' => 'available',
                    'title' => Yii::t('yiiplus/desktop', '分配'),
                ]);?>
                <br><br>
                <?=Html::a('&lt;&lt;' . $animateIcon, ['remove'], [
                    'class' => 'btn btn-danger btn-assign',
                    'data-target' => 'assigned',
                    'title' => Yii::t('yiiplus/desktop', '移除'),
                ]);?>
            </div>
            <div class="col-sm-5">
                <input class="form-control search" data-target="assigned"
                       placeholder="<?=Yii::t('yiiplus/desktop', '搜素已分配的路由');?>">
                <select multiple size="20" class="form-control list" data-target="assigned"></select>
            </div>
        </div>
    </div>
    <div class="box-footer"></div>
</div>