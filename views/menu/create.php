<?php
/**
 * yiiplus/yii2-desktop
 *
 * @category  PHP
 * @package   Yii2
 * @copyright 2018-2019 YiiPlus Ltd
 * @license   https://github.com/yiiplus/yii2-desktop/licence.txt Apache 2.0
 * @link      http://www.yiiplus.com
 */

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