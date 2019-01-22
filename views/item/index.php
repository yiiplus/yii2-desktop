<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yiiplus\desktop\components\RouteRule;
use yiiplus\desktop\components\Configs;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel yiiplus\desktop\models\searchs\AuthItem */
/* @var $context yiiplus\desktop\components\ItemController */

$context = $this->context;
$labels = $context->labels();
$this->title = Yii::t('yiiplus/desktop', $labels['Items']);
$this->params['breadcrumbs'][] = $this->title;

$rules = array_keys(Configs::authManager()->getRules());
$rules = array_combine($rules, $rules);
unset($rules[RouteRule::RULE_NAME]);
?>


<div class="box box-primary dataTables_wrapper role-index">
    <div class="box-header">
        <div class="no-margin pull-left">
            <?= Html::a(Yii::t('yiiplus/desktop', 'Create ' . $labels['Item']), ['create'], ['class' => 'btn btn-primary']) ?>
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
                'attribute' => 'name',
                'label' => Yii::t('yiiplus/desktop', '名称'),
            ],
            [
                'attribute' => 'ruleName',
                'label' => Yii::t('yiiplus/desktop', '规则名称'),
                'filter' => $rules
            ],
            [
                'attribute' => 'description',
                'label' => Yii::t('yiiplus/desktop', '描述'),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                "header" => "操作",
                'headerOptions' => ['width' => '10'],
            ],
        ],
    ])
    ?>
    <?php Pjax::end(); ?>
    </div>
    <div class="box-footer"></div>
</div>