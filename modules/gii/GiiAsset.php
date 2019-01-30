<?php
/**
 * yiiplus\desktop
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    zhouyang@mocaapp.com
 * @copyright 2006-2018 YiiPlus Ltd
 * @link      http://www.yiiplus.com
 */

namespace yiiplus\desktop\modules\gii;

use yii\web\AssetBundle;

/**
 * 引入样式文件
 */
class GiiAsset extends AssetBundle
{
    public $sourcePath = '@yiiplus/desktop/modules/gii/assets';
    public $css = [
        'main.css',
    ];
    public $depends = [
        'yii\gii\GiiAsset',
    ];
}
