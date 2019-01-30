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

use Yii;
use yii\helpers\Inflector;

class Module extends \yii\gii\Module
{
    public $allowedIPs = ['*'];

    /*
     * 控制器名称空间
     */
    public $controllerNamespace = 'yiiplus\desktop\modules\gii\controllers';

    /**
     * gii 生成配置
     */
    public $generators = [
        'crud' => [
            'class' => 'yii\gii\generators\crud\Generator',
            'templates' => [
                'default' => 'vendor/yiiplus/yii2-desktop/modules/gii/generators/crud/default'
            ]
        ],
        'model' => [
            'class' => 'yiiplus\desktop\modules\gii\generators\model\Generator',
            'useTablePrefix' => true,
            'ns' => 'yiiplus\desktop\models'
        ]
    ];

    /*
     * 源语言
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

