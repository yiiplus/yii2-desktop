<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\YiiAsset;

$userName = $model->{$usernameField};
if (!empty($fullnameField)) {
    $userName .= ' (' . ArrayHelper::getValue($model, $fullnameField) . ')';
}
$userName = Html::encode($userName);

$this->title = Yii::t('yiiplus/desktop', '分配管理') . ' : ' . $userName;

$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', '分配列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $userName;

$opts = Json::htmlEncode([
    'items' => $model->getItems(),
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
?>

<div class="box box-primary assignment-index">
    <div class="box-header"></div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-5">
                <input class="form-control search" data-target="available" placeholder="<?=Yii::t('yiiplus/desktop', '搜索可用');?>">
                <select multiple size="20" class="form-control list" data-target="available">
                </select>
            </div>
            <div class="col-sm-2" align="center">
                <br><br><br><br>
                <?=Html::a('&gt;&gt;' . $animateIcon, ['assign', 'id' => (string) $model->id], [
                    'class' => 'btn btn-success btn-assign',
                    'data-target' => 'available',
                    'title' => Yii::t('yiiplus/desktop', '分配'),
                ]);?>
                <br><br>
                <?=Html::a('&lt;&lt;' . $animateIcon, ['revoke', 'id' => (string) $model->id], [
                    'class' => 'btn btn-danger btn-assign',
                    'data-target' => 'assigned',
                    'title' => Yii::t('yiiplus/desktop', '移除'),
                ]);?>
            </div>
            <div class="col-sm-5">
                <input class="form-control search" data-target="assigned" placeholder="<?=Yii::t('yiiplus/desktop', '搜索已分配');?>">
                <select multiple size="20" class="form-control list" data-target="assigned">
                </select>
            </div>
        </div>
    </div>
    <div class="box-footer"></div>
</div>
