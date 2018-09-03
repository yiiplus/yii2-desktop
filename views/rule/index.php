<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('yiiplus/desktop', 'Rules');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary dataTables_wrapper role-index">
    <div class="box-header">
        <div class="no-margin pull-left">
            <?= Html::a(Yii::t('yiiplus/desktop', 'Create Rule'), ['create'], ['class' => 'btn btn-primary']) ?>
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
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'name',
                    'label' => Yii::t('yiiplus/desktop', 'Name'),
                ],
                ['class' => 'yii\grid\ActionColumn',],
            ],
        ]);
        ?>
        <?php Pjax::end(); ?>
    </div>
</div>