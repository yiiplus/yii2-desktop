<?php
use yii\helpers\Html;

$this->title = Yii::t('yiiplus/desktop', '新增菜单') . ': ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', '菜单列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('yiiplus/desktop', '更新');
?>

<div class="box box-primary menu-update">
    <div class="box-header with-border"></div>
	<div class="box-body">
		<?= $this->render('_form', ['model' => $model]) ?>
	</div>
	<div class="box-footer"></div>
</div>