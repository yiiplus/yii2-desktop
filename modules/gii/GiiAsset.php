<?php

namespace yiiplus\desktop\modules\gii;

use yii\web\AssetBundle;

/**
 * table prompt This declares the asset files required by Gii.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
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
