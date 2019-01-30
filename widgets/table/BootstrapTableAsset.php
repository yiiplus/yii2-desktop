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

namespace yiiplus\desktop\widgets\table;

use yii\base\Exception;
use yii\web\AssetBundle;

/**
 * 树形组件样式路径
 *
 * @author Hongbin Chen <hongbin.chen@aliyun.com>
 * @since 2.0.0
 */
class BootstrapTableAsset extends AssetBundle {

    public $sourcePath = '@vendor/wenzhixin/bootstrap-table/dist';

    public $css = [
        'bootstrap-table.min.css',
    ];

    public $js = [
        'bootstrap-table.min.js',
        'bootstrap-table-locale-all.min.js',
        'https://unpkg.com/tableexport.jquery.plugin@1.10.1/tableExport.min.js',
        'extensions/export/bootstrap-table-export.min.js',
        'extensions/multiple-sort/bootstrap-table-multiple-sort.js',
        'extensions/toolbar/bootstrap-table-toolbar.min.js',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
