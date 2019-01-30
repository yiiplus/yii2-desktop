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

namespace yiiplus\desktop\modules\gii;

use yii\web\AssetBundle;

/**
 * 引入样式文件
 */
class GiiAsset extends AssetBundle
{
    /**
     * 资源路径
     *
     * @var string
     */
    public $sourcePath = '@yiiplus/desktop/modules/gii/assets';

    /**
     * css引入
     *
     * @var array
     */
    public $css = [
        'main.css',
    ];

    /**
     * 依赖
     *
     * @var array
     */
    public $depends = [
        'yii\gii\GiiAsset',
    ];
}
