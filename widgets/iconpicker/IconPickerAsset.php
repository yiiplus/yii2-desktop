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

namespace yiiplus\desktop\widgets\iconpicker;


use yii\web\AssetBundle;

/**
 * 小图标样式文件路径
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    liguangquan@163.com
 * @copyright 2006-2018 YiiPlus Ltd
 * @link      http://www.yiiplus.com
 */
class IconPickerAsset extends AssetBundle
{
    public $sourcePath = '@yiiplus/desktop/widgets/iconpicker/static';
    public $css=[
        'css/bootstrap-iconpicker.min.css'
    ];
    public $js= [
        'js/iconset/iconset-fontawesome-4.2.0.min.js',
        'js/bootstrap-iconpicker.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}