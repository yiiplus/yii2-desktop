<?php

use yii\helpers\Html;

$this->title = Yii::t('yiiplus/desktop', '新增规则');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', '规则列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary rule-create">
    <div class="box-header"></div>
	<div class="box-body">
		<?= $this->render('_form', ['model' => $model]) ?>
	</div>
	<div class="box-footer"></div>
</div>