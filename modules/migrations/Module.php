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

namespace yiiplus\desktop\modules\migrations;

use Yii;
use yii\helpers\Inflector;

/**
 * 迁移Module
 *
 * @author zhangxu <2217418603@qq.com>
 * @since 2.0.0
 */
class Module extends \yii\base\Module
{
    // 控制器名称空间
    public $controllerNamespace = 'yiiplus\desktop\modules\migrations\controllers';

    // 源语言
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
