<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yiiplus\desktop\components\Helper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('yiiplus/desktop', '操作日志');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary dataTables_wrapper desktop-log-index">
    <div class="box-header"></div>
    <div class="box-body">
    <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                'route',
                [
                    'attribute' => 'user_id',
                    'value' => function($model) {
                        return \yiiplus\desktop\models\User::findOne($model->user_id)->username;
                    }
                ],
                [
                    'attribute' => 'ip',
                    'value' => function($model) {
                        return long2ip($model->ip);
                    }
                ],
                'created_at:datetime',
                [
                    'class' => 'yii\grid\ActionColumn',
                    "header" => "操作",
                    'template' => '{view}'
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
    </div>
    <div class="box-footer"></div>
</div>