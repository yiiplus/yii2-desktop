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

namespace yiiplus\desktop\widgets\tree;

use yii\web\AssetBundle;

/**
 * 树形组件样式路径
 *
 * @author liguangquan <liguangquan@163.com>
 * @since 2.0.0
 */
class TreeGridAsset extends AssetBundle {
    /**
     * 样式文件目录
     *
     * @var string
     */
    public $sourcePath = '@yiiplus/desktop/widgets/tree/assets';

    /**
     * js文件路径
     *
     * @var string
     */
    public $js = [
        'js/jquery.treegrid.min.js',
    ];
    /**
     * css文件路径
     *
     * @var string
     */
    public $css = [
        'css/jquery.treegrid.css',
    ];
    /**
     * yii2 jquery 路径
     *
     * @var string
     */
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
