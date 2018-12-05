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
    
    /**
     * {@inheritdoc}
     */
    public $js = [
        'jquery-ui.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}