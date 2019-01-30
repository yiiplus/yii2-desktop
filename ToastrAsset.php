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

namespace yiiplus\desktop;

use yii\web\AssetBundle;

/**
 * 样式文件引入
 *
 * @author liguangquan <liguangquan@163.com>
 * @since 2.0.0
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