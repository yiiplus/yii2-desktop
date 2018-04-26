<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yiiplus\desktop\components\Helper;

$this->title = Yii::t('rbac-admin', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary dataTables_wrapper user-index">
    <div class="box-header">
        <div class="no-margin pull-left">
            <?= Html::a(Yii::t('rbac-admin', 'Create'), ['create'], ['class' => 'btn btn-primary']) ?> 
            <?= Html::a(Yii::t('rbac-admin', 'Activate'), ['activate'], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('rbac-admin', 'Inactive'), ['inactive'], ['class' => 'btn btn-primary']) ?> 
            <?= Html::a(Yii::t('rbac-admin', 'Delete'), ['delete'], ['class' => 'btn btn-danger']) ?>
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
                'label' => Yii::t('rbac-admin', 'ID'),
                'headerOptions' => ['width' => '100'],
            ],
            [
                'label' => Yii::t('rbac-admin', 'Username'),
                'attribute' => 'username',
            ],
            'email:email',
            [
                'label' => Yii::t('rbac-admin', 'CreatedAt'),
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],
            [
                'attribute' => 'status',
                'label' => Yii::t('rbac-admin', 'Status'),
                'value' => function($model) {
                    return $model->status == 0 ? Yii::t('rbac-admin', 'Inactive') : Yii::t('rbac-admin', 'Activate');
                },
                'filter' => [
                    0 => Yii::t('rbac-admin', 'Inactive'),
                    10 => Yii::t('rbac-admin', 'Activate'),
                ]
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                "header" => "操作",
                'headerOptions' => ['width' => '10'],
                'template'=>'{view} {update} {assignment} {activate} {inactive} {delete}',
                'buttons' => [
                    'assignment' => function($url, $model) {
                        $options = [
                            'title' => Yii::t('rbac-admin', 'Assignment'),
                            'aria-label' => Yii::t('rbac-admin', 'Assignment'),
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
                            'title' => Yii::t('rbac-admin', 'Activate'),
                            'aria-label' => Yii::t('rbac-admin', 'Activate'),
                            'data-confirm' => Yii::t('rbac-admin', 'Are you sure you want to activate this user?'),
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
                            'title' => Yii::t('rbac-admin', 'Inactive'),
                            'aria-label' => Yii::t('rbac-admin', 'Inactive'),
                            'data-confirm' => Yii::t('rbac-admin', 'Are you sure you want to inactive this user?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, $options);
                    },
                    'delete' => function($url, $model) {
                        // 超级管理员无法删除
                        if ($model->id != 1 && Yii::$app->getUser()->id != $model->id) {
                            $options = [
                                'title' => Yii::t('rbac-admin', 'Delete'),
                                'aria-label' => Yii::t('rbac-admin', 'Delete'),
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