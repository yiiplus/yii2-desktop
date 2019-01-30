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

namespace yiiplus\desktop\widgets\iconpicker;


use yii\web\AssetBundle;

/**
 * 小图标样式路径
 *
 * @author liguangquan <liguangquan@163.com>
 * @since 2.0.0
 */
class IconPickerAsset extends AssetBundle
{
    /**
     * 样式文件路径
     *
     * @var string
     */
    public $sourcePath = '@yiiplus/desktop/widgets/iconpicker/assets';

    /**
     * css 文件
     *
     * @var array
     */
    public $css=[
        'css/bootstrap-iconpicker.min.css'
    ];

    /**
     * js 文件
     *
     * @var array
     */
    public $js= [
        'js/iconset/iconset-fontawesome-4.2.0.min.js',
        'js/bootstrap-iconpicker.min.js',
    ];

    /**
     * 集成 yii2->jquery
     *
     * @var array
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}