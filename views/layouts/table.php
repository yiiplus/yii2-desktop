<?php

use yii\helpers\Html;
use yiiplus\desktop\widgets\table\ToolbarView;

yiiplus\desktop\widgets\table\BootstrapTableAsset::register($this);
$this->registerJs("$.extend($.fn.bootstrapTable.defaults.icons, { advancedSearchIcon: 'glyphicon-search'});"); // 高级搜索图标替换
$this->registerJs("$('#${id}').bootstrapTable(".json_encode($options).");");

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