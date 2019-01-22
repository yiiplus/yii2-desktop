<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = Yii::t('yiiplus/desktop', '操作日志');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', '操作日志'), 'url' => ['index']];
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
