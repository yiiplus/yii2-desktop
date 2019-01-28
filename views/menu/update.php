<?php
use yii\helpers\Html;

$this->title = Yii::t('yiiplus/desktop', '更新菜单') . ': ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', '菜单列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('yiiplus/desktop', '更新');
?>
<div class="col-md-12">
    <div class="box box-info desktop-menu-update">
        <div class="box-header"> </div>
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</div>