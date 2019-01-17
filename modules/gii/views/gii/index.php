<?php
/**
 * 慧诊
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    zhouyang <zhouyang@himoca.com>
 * @copyright 2017-2019 北京慧诊科技有限公司
 * @license   https://www.huizhen.com/licence.txt Licence
 * @link      http://www.huizhen.com
 */

use yii\helpers\Html;

//获取gii module信息
$generators = Yii::$app->controller->module->generators;
$this->title = Yii::t('yiiplus/desktop', 'Gii');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary dataTables_wrapper user-index">
    <div class="box-header">
    </div>

<div class="default-index">
	<div class="row">
        <?php foreach ($generators as $id => $generator):?>
        <div class="col-md-4">
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title"><?= Html::encode($generator->getName()) ?></h3>
				</div>
				<div class="box-body">
					<p><?= $generator->getDescription() ?></p>
					<p><?= Html::a('Start »', ['gii/view', 'id' => $id], ['class' => 'btn btn-default']) ?></p>
				</div>
			</div>
		</div>
        <?php endforeach;?>
    </div>
</div>
