<?php
use yiiplus\desktop\components\Html;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use yii\widgets\Pjax;

use yiiplus\desktop\widgets\tree\TreeGrid;

$this->title = Yii::t('yiiplus/desktop', '菜单列表');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box desktop-menu-index">

    <div class="box-header with-border">
        <div class="pull-left">
            <div class="btn-group pull-left" style="margin-left: 10px">
                <a href="create" class="btn btn-sm btn-info" title="新增">
                    <i class="fa fa-save"></i><span class="hidden-xs">&nbsp;&nbsp;新增</span>
                </a>
            </div>
        </div>
    </div>

    <div class="box-body">
    <?php Pjax::begin(); ?>
        <?= TreeGrid::widget([
            'dataProvider' => $dataProvider,
            'keyColumnName' => 'id',
            'parentColumnName' => 'parent',
            'parentRootValue' => null,
            
            // 'pluginOptions' => [ // 菜单收起
            //     'initialState' => 'collapse',
            // ],

            'columns' => [
                'name',
                'route',
                [
                    'attribute' => 'icon',
                    'value' => function($model) {
                        return Html::icon($model->icon);
                    },
                    'format' => 'raw'
                ],
                [
                    'class' => 'yiiplus\desktop\widgets\tree\PositionColumn',
                    'attribute' => 'order'
                ],
                [
                    'header' => Yii::t('yiiplus/desktop','操作'),
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{create} {view} {update} {delete}',
                    'buttons' => [
                        'create' => function($url, $model) {
                            return Html::a(Html::icon('plus'), ['create', 'id' => $model->id], ['class' => 'btn btn-default btn-xs']);
                        },
                        'view' => function($url, $model) {
                            return Html::a(Html::icon('eye'), ['view', 'id' => $model->id], ['class' => 'btn btn-default btn-xs']);
                        },
                        'update' => function($url, $model) {
                            return Html::a(Html::icon('pencil'), ['update', 'id' => $model->id], ['class' => 'btn btn-default btn-xs']);
                        },
                        'delete' => function($url, $model) {
                            return Html::a(Html::icon('trash'), ['delete', 'id' => $model->id], ['class' => 'btn btn-default btn-xs']);
                        },
                    ]
                ],
            ],
        ]);
        ?>
    <?php Pjax::end(); ?>
    </div>
</div>