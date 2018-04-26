<?php

namespace yiiplus\desktop;

use yii\web\AssetBundle;

/**
 * Description of DesktopAsset
 */
class DesktopAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@yiiplus/desktop/assets';
    /**
     * @inheritdoc
     */
    public $css = [
        'desktop.css',
    ];
}