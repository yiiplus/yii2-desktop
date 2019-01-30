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
use yiiplus\desktop\widgets\table\ToolbarView;

yiiplus\desktop\widgets\table\BootstrapTableAsset::register($this);

$this->registerJs("$.extend($.fn.bootstrapTable.defaults.icons, { advancedSearchIcon: 'glyphicon-search'});"); // 高级搜索图标替换
$this->registerJs("$('#${id}').bootstrapTable(".json_encode($options).");");
// $this->registerJs("$('#${id}').on('load-success.bs.table', function (data) { toastr.success('" . Yii::t('yiiplus/desktop', '加载成功') . "') });");
// $this->registerJs("$('#${id}').on('load-error.bs.table', function (status, jqXHR) { toastr.error('" . Yii::t('yiiplus/desktop', '加载失败') . "') });");

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <?php echo ToolbarView::widget($toolbar); ?>
        <?php echo $table; ?>
    </div>
    <div class="box-footer"></div>
</div>
<?php $this->beginBlock('js'); ?>
<script type="text/javascript">

</script>
<?php $this->endBlock('js'); ?>