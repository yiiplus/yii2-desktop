<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yiiplus\desktop\components\Helper;

$this->title = Yii::t('yiiplus/desktop', '用户列表');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary dataTables_wrapper user-index">
    <div class="box-header">
        <div class="no-margin pull-left">
            <?= Html::a(Yii::t('yiiplus/desktop', '创建'), ['create'], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('yiiplus/desktop', '启用'), ['activate'], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('yiiplus/desktop', '禁用'), ['inactive'], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('yiiplus/desktop', '删除'), ['delete'], ['class' => 'btn btn-danger']) ?>
        </div>
        <div class="no-margin pull-right">
            <button type="button" class="btn btn-default"><i class="fa fa-cog"></i></button>
            <button type="button" class="btn btn-default"><i class="fa fa-refresh"></i></button>
            <button type="button" class="btn btn-default"><i class="fa fa-save"></i></button>
            <button type="button" class="btn btn-default"><i class="fa fa-arrows-alt"></i></button>
        </div>
    </div>
    <div class="box-body">
    <?php Pjax::begin(); ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered table-hover'],
        'layout' => '{items}<div class="dataTables_info pull-left">{summary}</div><div class="dataTables_paginate pull-right">{pager}</div>',
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'headerOptions' => ['width' => '10'],
            ],
            [
                'attribute' => 'id',
                'label' => Yii::t('yiiplus/desktop', 'ID'),
                'headerOptions' => ['width' => '100'],
            ],
            [
                'label' => Yii::t('yiiplus/desktop', '用户名'),
                'attribute' => 'username',
            ],
            'email:email',
            [
                'label' => Yii::t('yiiplus/desktop', '创建时间'),
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],
            [
                'attribute' => 'status',
                'label' => Yii::t('yiiplus/desktop', '状态'),
                'value' => function($model) {
                    return $model->status == 0 ? Yii::t('yiiplus/desktop', '禁用') : Yii::t('yiiplus/desktop', '启用');
                },
                'filter' => [
                    0 => Yii::t('yiiplus/desktop', '禁用'),
                    10 => Yii::t('yiiplus/desktop', '启用'),
                ]
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                "header" => Yii::t('yiiplus/desktop', "操作"),
                'headerOptions' => ['width' => '10'],
                'template'=>'{view} {update} {assignment} {activate} {inactive} {delete}',
                'buttons' => [
                    'assignment' => function($url, $model) {
                        $options = [
                            'title' => Yii::t('yiiplus/desktop', '分配管理'),
                            'aria-label' => Yii::t('yiiplus/desktop', '分配管理'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ];

                        return Html::a('<span class="glyphicon glyphicon-cog"></span>', ['assignment/view', 'id' => $model->id], $options);
                    },
                    'activate' => function($url, $model) {
                        if ($model->id == 1 || $model->status == 10) {
                            return '';
                        }
                        $options = [
                            'title' => Yii::t('yiiplus/desktop', '启用'),
                            'aria-label' => Yii::t('yiiplus/desktop', '禁用'),
                            'data-confirm' => Yii::t('yiiplus/desktop', '确认要激活该用户?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, $options);
                    },
                    'inactive' => function($url, $model) {
                        if ($model->id == 1 || $model->status != 10) {
                            return '';
                        }
                        $options = [
                            'title' => Yii::t('yiiplus/desktop', '禁用'),
                            'aria-label' => Yii::t('yiiplus/desktop', '禁用'),
                            'data-confirm' => Yii::t('yiiplus/desktop', '确认要禁用该用户?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, $options);
                    },
                    'delete' => function($url, $model) {
                        // 超级管理员无法删除
                        if ($model->id != 1 && Yii::$app->getUser()->id != $model->id) {
                            $options = [
                                'title' => Yii::t('yiiplus/desktop', '删除'),
                                'aria-label' => Yii::t('yiiplus/desktop', '删除'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ];
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                        }
                    },
                ]
            ],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
    </div>
    <div class="box-footer"></div>
</div>