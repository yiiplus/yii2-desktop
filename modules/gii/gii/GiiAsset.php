<?php
/**
 * 慧诊
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    zhouyang <zhouyang@himoca.com>
 * @copyright 2017-2019 北京慧诊科技有限公司
 * @license   https://www.huizhen.com/licence.txt Licence
 * @link      http://www.huizhen.com
 */

namespace yiiplus\desktop\modules\gii;

use yii\web\AssetBundle;

/**
 * 引入样式文件
 */
class GiiAsset extends AssetBundle
{
    public $sourcePath = '@base/vendor/yiiplus/yii2-desktop/modules/gii/assets';
    public $css = [
        'main.css',
    ];
    public $depends = [
        'yii\gii\GiiAsset',
    ];
}
