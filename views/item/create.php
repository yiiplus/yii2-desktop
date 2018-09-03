<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model yiiplus\desktop\models\AuthItem */
/* @var $context yiiplus\desktop\components\ItemController */

$context = $this->context;
$labels = $context->labels();
$this->title = Yii::t('yiiplus/desktop', 'Create ' . $labels['Item']);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary auth-item-create">
    <div class="box-header with-border"></div>
	<div class="box-body">
		<?= $this->render('_form', ['model' => $model]) ?>
	</div>
	<div class="box-footer"></div>
</div>