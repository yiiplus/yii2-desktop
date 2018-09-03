<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model yiiplus\desktop\models\AuthItem */
/* @var $context yiiplus\desktop\components\ItemController */

$context = $this->context;
$labels = $context->labels();
$this->title = Yii::t('yiiplus/desktop', 'Update ' . $labels['Item']) . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('yiiplus/desktop', 'Update');
?>

<div class="box box-primary auth-item-update">
    <div class="box-header with-border"></div>
	<div class="box-body">
		<?= $this->render('_form', ['model' => $model]) ?>
	</div>
	<div class="box-footer"></div>
</div>