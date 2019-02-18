<?php
/**
 * yiiplus/yii2-desktop
 *
 * @category  PHP
 * @package   Yii2
 * @copyright 2018-2019 YiiPlus Ltd
 * @license   https://github.com/yiiplus/yii2-desktop/licence.txt Apache 2.0
 * @link      http://www.yiiplus.com
 */

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
            <?= Html::a(Yii::t('yiiplus/desktop', '创建 ' . $labels['Item']), ['create'], ['class' => 'btn btn-primary']) ?>
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
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value' => function ($model) {
                        static $manager;
                        if (!isset($manager)) {
                            $manager = Configs::authManager();
                        }

                        $roles = $this->context->id == 'role' ? array_keys($manager->getChildRoles($model->name)) : [];
                        $str = '';
                        foreach ($roles as $role) {
                            $str .= "<span  class='label label-success'>" . $role . "</span></br>";
                        }
                        return $str;
                    },
                    'visible' => $this->context->id == 'role',
                ],
                [
                    'attribute' => '权限',
                    'format' => 'raw',
                    'value' => function ($model) {
                        static $manager;
                        if (!isset($manager)) {
                            $manager = Configs::authManager();
                        }

                        static $obj;
                        if (!isset($obj[$model->name])) {
                            $obj[$model->name] = array_keys($manager->getPermissionsByRole($model->name));
                        }
                        $str = '';
                        foreach ($obj[$model->name] as $item) {
                            if ($item['0'] != '/') {
                                $str .= "<span  class='label label-success'>" . $item . "</span></br>";
                            }
                        }
                        return $str;
                    },
                ],
                [
                    'attribute' => '路由',
                    'format' => 'raw',
                    'value' => function ($model) {
                        static $manager;
                        if (!isset($manager)) {
                            $manager = Configs::authManager();
                        }

                        static $obj;
                        if (!isset($obj[$model->name])) {
                            $obj[$model->name] = array_keys($manager->getPermissionsByRole($model->name));
                        }
                        $str = '';
                        foreach ($obj[$model->name] as $item) {
                            if ($item['0'] == '/') {
                                $str .= "<span  class='label label-success'>" . $item . "</span></br>";
                            }
                        }
                        return $str;
                    },
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
                    "header" => Yii::t('yiiplus/desktop', "操作"),
                    'headerOptions' => ['width' => '10'],
                ],
            ],
        ])
        ?>
        <?php Pjax::end(); ?>
    </div>
    <div class="box-footer"></div>
</div>