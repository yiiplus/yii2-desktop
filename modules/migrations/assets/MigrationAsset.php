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

namespace yiiplus\desktop\modules\migrations\assets;

/**
 * 引入静态文件
 *
 * @author zhangxu <2217418603@qq.com>
 * @since 2.0.0
 */
class MigrationAsset extends  \yii\web\AssetBundle
{
    /**
     * 引入css文件
     *
     * @var array
     */
    public $css = [
        '\migration.css',
    ];

    /**
     * 引入js文件
     *
     * @var array
     */
    public $js = [
        '\migration.js'
    ];

    /**
     * 引入默认资源
     *
     * @var array
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    /**
     * 初始化
     */
    public function init()
    {
        parent::init();
        $this->sourcePath = dirname(__DIR__) . '/static';
    }
}