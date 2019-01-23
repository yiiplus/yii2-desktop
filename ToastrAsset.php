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

use yii\web\AssetBundle;

/**
 * 样式文件引入
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    liguangquan@163.com
 * @copyright 2006-2018 YiiPlus Ltd
 * @link      http://www.yiiplus.com
 */
class ToastrAsset extends AssetBundle
{
    public $sourcePath = '@bower/toastr';
    public $css = [
        'toastr.min.css',
    ];
    public $js = [
        'toastr.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}