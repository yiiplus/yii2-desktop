<?php
use yii\helpers\Html;

$this->title = Yii::t('yiiplus/desktop', 'Create Menu');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary menu-create">
    <div class="box-header with-border"></div>
	<div class="box-body">
		<?= $this->render('_form', ['model' => $model]) ?>
	</div>
	<div class="box-footer"></div>
</div>