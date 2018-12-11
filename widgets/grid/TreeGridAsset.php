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

namespace yiiplus\desktop\widgets\grid;

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
class TreeGridAsset extends AssetBundle {

    public $sourcePath = '@yiiplus/desktop/widgets/grid/assets/tree';

    /**
     * js文件路径
     * @var string
     */
    public $js = [
        'js/jquery.treegrid.min.js',
    ];
    /**
     * css文件路径
     * @var [type]
     */
    public $css = [
        'css/jquery.treegrid.css',
    ];
    /**
     * yii2 jquery路径
     * @var [type]
     */
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
