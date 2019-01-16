<?php
namespace yiiplus\desktop\modules\migrations\assets;

class MigrationAsset extends  \yii\web\AssetBundle
{
    public $css = [
        '\migration.css',
    ];
    public $js = [
        '\migration.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    public function init()
    {
        parent::init();
        $this->sourcePath = dirname(__DIR__) . '/static';
    }
}