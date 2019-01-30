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
use yii\widgets\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', '规则列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary rule-view">
    <div class="box-header"></div>
    <div class="box-body">
        <p>
            <?= Html::a(Yii::t('yiiplus/desktop', '更新'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
            <?php
            echo Html::a(Yii::t('yiiplus/desktop', '删除'), ['delete', 'id' => $model->name], [
                'class' => 'btn btn-danger',
                'data-confirm' => Yii::t('yiiplus/desktop', '确定要删除该规则吗?'),
                'data-method' => 'post',
            ]);
            ?>
        </p>

        <?php
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'name',
                'className',
            ],
        ]);
        ?>
    </div>
    <div class="box-footer"></div>
</div>