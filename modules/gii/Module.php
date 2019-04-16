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

use Yii;
use yii\helpers\Inflector;

/**
 * Module 模块
 */
class Module extends \yii\gii\Module
{
    /**
     * ip白名单
     *
     * @var array
     */
    public $allowedIPs = ['*'];

    /**
     * 控制器名称空间
     *
     * @var string
     */
    public $controllerNamespace = 'yiiplus\desktop\modules\gii\controllers';

    /**
     * gii 生成配置
     *
     * @var array
     */
    public $generators = [
        'crud' => [
            'class' => 'yii\gii\generators\crud\Generator',
            'templates' => [
                'default' => '/www/vendor/yiiplus/yii2-desktop/modules/gii/generators/crud/default'
            ]
        ],
        'model' => [
            'class' => 'yiiplus\desktop\modules\gii\generators\model\Generator',
            'useTablePrefix' => true,
            'ns' => 'yiiplus\desktop\models'
        ]
    ];

    /**
     * 源语言
     *
     * @var string
     */
    public $sourceLanguage = 'en';

    /**
     * 初始化
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        
        // i18n
        $this->registerTranslations();
    }

    /**
     * 注册翻译文件
     *
     * @return void
     */
    protected function registerTranslations()
    {
        Yii::$app->i18n->translations['yiiplus/desktop'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => $this->sourceLanguage,
            'basePath' => '@yiiplus/desktop/messages',
            'fileMap' => [
                'yiiplus/desktop' => 'desktop.php',
            ],
        ];
    }

    /**
     * 多语言翻译
     *
     * @param string  $message  消息
     * @param array   $params   参数
     * @param string  $language 语言
     *
     * @return string 翻译结果
     */
    public static function t($message, $params = [], $language = null)
    {
        return Yii::t('yiiplus/desktop', $message, $params, $language);
    }
}

