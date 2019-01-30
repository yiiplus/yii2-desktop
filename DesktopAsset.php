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
    ];
    
    /**
     * js文件
     *
     * @var string
     */
    public $js = [
        'desktop.js',
    ];
    
    /**
     * 引入yii2默认jquery
     *
     * @var string
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'yiiplus\desktop\AdminLteAsset',
        'yiiplus\desktop\FontAwesomeAsset',
        'yiiplus\desktop\ToastrAsset',
    ];
}
