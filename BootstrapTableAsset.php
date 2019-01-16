<?php
/**
 * yiiplus\desktop
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    liguangquan@163.com
 * @copyright 2006-2018 YiiPlus Ltd
 * @link      http://www.yiiplus.com
 */

namespace yiiplus\desktop;

use yii\base\Exception;
use yii\web\AssetBundle;

/**
 * 树形组件样式路径
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    liguangquan@163.com
 * @copyright 2006-2018 YiiPlus Ltd
 * @link      http://www.yiiplus.com
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
