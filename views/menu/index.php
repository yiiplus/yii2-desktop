<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use yii\widgets\Pjax;

use yiiplus\desktop\widgets\grid\TreeGrid;

$this->title = Yii::t('yiiplus/desktop', 'Menus');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary dataTables_wrapper user-index">
    <div class="box-body">
    <?php Pjax::begin(); ?>
        <?= TreeGrid::widget([
            'dataProvider' => $dataProvider,
            'keyColumnName' => 'id',
            'parentColumnName' => 'parent',
            'parentRootValue' => null,
            
            'pluginOptions' => [ //jquery默认收起
                'initialState' => 'collapse',
            ],
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
                    'class' => 'yiiplus\desktop\widgets\grid\PositionColumn',
                    'attribute' => 'order'
                ],
                [
                    'header' => '操作',
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