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

namespace yiiplus\desktop;

use Yii;
use yii\helpers\Inflector;

/**
 * GUI manager for RBAC.
 *
 * @author Hongbin Chen <hongbin.chen@aliyun.com>
 * @since 2.0.0
 */
class Module extends \yii\base\Module
{
    // 控制器名称空间
    public $controllerNamespace = 'yiiplus\desktop\controllers';

    // 源语言
    public $sourceLanguage = 'en';

    // 默认路由
    public $defaultRoute = 'assignment';

    // 默认面包屑地址
    public $defaultUrl;

    // 默认面包屑标题
    public $defaultUrlLabel;

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

        // 模块嵌套
        $this->modules = [
            'gii' => [
                'class' => 'yiiplus\desktop\modules\gii\Module',
            ],
            'migrations' => [
                'class' => 'yiiplus\desktop\modules\migrations\Module',
            ],
        ];
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
    
    /**
     * 前置动作
     *
     * @param  object $action 动作对象
     *
     * @return boolean 状态
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $view = $action->controller->getView();
            $view->params['breadcrumbs'][] = [
                'label' => ($this->defaultUrlLabel ?: Yii::t('yiiplus/desktop', '系统设置')),
                'url' => ['/' . ($this->defaultUrl ?: $this->uniqueId)],
            ];
            return true;
        }
        return false;
    }
}
