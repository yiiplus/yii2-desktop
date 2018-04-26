<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use yii\widgets\Pjax;

$this->title = Yii::t('rbac-admin', 'Menus');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary dataTables_wrapper user-index">
    <div class="box-header">
        <div class="no-margin pull-left">
            <?= Html::a(Yii::t('rbac-admin', 'Create'), ['create'], ['class' => 'btn btn-primary']) ?>
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
        'filterModel'  => $searchModel,
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
            'name',
            [
                'attribute' => 'menuParent.name',
                'filter' => Html::activeTextInput($searchModel, 'parent_name', ['class' => 'form-control', 'id' => null]),
                'label' => Yii::t('rbac-admin', 'Parent'),
            ],
            'route',
            ['attribute'=>'order','headerOptions' => ['width' => '100']],
            [
                'class' => 'yii\grid\ActionColumn',
                "header" => "操作",
                'headerOptions' => ['width' => '10'],
            ],
        ],  
    ]);
    ?>
    <?php Pjax::end(); ?>
    </div>
</div>