<?php
use yii\helpers\Html;

$this->title = Yii::t('yiiplus/desktop', 'Create Menu');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12">
    <div class="box box-info desktop-menu-create">
        <div class="box-header"> </div>
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</div>