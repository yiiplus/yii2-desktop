<?php

use yii\helpers\Html;

$this->title = Yii::t('yiiplus/desktop', '更新规则') . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', '规则列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('yiiplus/desktop', '更新');
?>

<div class="box box-primary rule-update">
    <div class="box-header"></div>
    <div class="box-body">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
    <div class="box-footer"></div>
</div>