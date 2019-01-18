<?php

use yii\helpers\Html;

$context = $this->context;
$labels = $context->labels();
$this->title = Yii::t('yiiplus/desktop', 'Create ' . $labels['Item']);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary auth-item-create">
    <div class="box-header with-border"></div>
    <div class="box-body">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
    <div class="box-footer"></div>
</div>