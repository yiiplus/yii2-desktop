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
class DesktopAsset extends AssetBundle
{
    /**
     * 样式文件路径
     *
     * @var string
     */
    public $sourcePath = '@yiiplus/desktop/assets';

    /**
     * css文件
     *
     * @var string
     */
    public $css = [
        'desktop.css',
        'jquery-ui.css',
    ];
    
    /**
     * js文件
     *
     * @var string
     */
    public $js = [
        'jquery-ui.js',
        'skin.js',
    ];
    
    /**
     * 引入yii2默认jquery
     *
     * @var string
     */
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
