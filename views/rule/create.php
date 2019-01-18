<?php

use yii\helpers\Html;

$this->title = Yii::t('yiiplus/desktop', 'Create Rule');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', 'Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary rule-create">
    <div class="box-header"></div>
    <div class="box-body">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
    <div class="box-footer"></div>
</div>