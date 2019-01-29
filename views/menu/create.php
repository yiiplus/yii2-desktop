<?php
use yii\helpers\Html;

$this->title = Yii::t('yiiplus/desktop', '创建菜单');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', '菜单列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12">
    <div class="box box-info desktop-menu-create">
        <div class="box-header"> </div>
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</div>