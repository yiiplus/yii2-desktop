<?php

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

    /*
     * 源语言
     */
    public $sourceLanguage = 'en';

    /**
     * @inheritdoc
     */
    public $defaultRoute = 'assignment';

    /**
     * @var array Nav bar items.
     */
    public $navbar;

    /**
     * 主模板配置文件
     */
    public $mainLayout = '@yiiplus/desktop/views/layouts/main.php';

    /**
     * gii生成配置
     */
    public $generators = [
        'crud' => [
            'class' => 'yii\gii\generators\crud\Generator',
            'templates' => [
                'default' => '@base/vendor/yiiplus/yii2-desktop/modules/gii/generators/crud/default'
            ]
        ],
        'model' => [
            'class' => 'yiiplus\desktop\modules\gii\generators\model\Generator',
            'useTablePrefix' => true,
            'ns' => 'yiiplus\desktop\models'
        ]
    ];

    /**
     * @var array
     * @see [[menus]]
     */
    private $_menus = [];

    /**
     * @var array
     * @see [[menus]]
     */
    private $_coreItems = [
        'user' => 'Users',
        'assignment' => 'Assignments',
        'role' => 'Roles',
        'permission' => 'Permissions',
        'route' => 'Routes',
        'rule' => 'Rules',
        'menu' => 'Menus',
    ];
    
    /**
     * @var array
     * @see [[items]]
     */
    private $_normalizeMenus;

    /**
     * @var string Default url for breadcrumb
     */
    public $defaultUrl;

    /**
     * @var string Default url label for breadcrumb
     */
    public $defaultUrlLabel;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();

        //user did not define the Navbar?
        if ($this->navbar === null && Yii::$app instanceof \yii\web\Application) {
            $this->navbar = [
                ['label' => Yii::t('yiiplus/desktop', 'Help'), 'url' => ['default/index']],
                ['label' => Yii::t('yiiplus/desktop', 'Application'), 'url' => Yii::$app->homeUrl],
            ];
        }
        if (class_exists('yii\jui\JuiAsset')) {
            Yii::$container->set('yiiplus\desktop\AutocompleteAsset', 'yii\jui\JuiAsset');
        }

        $class = new \ReflectionClass($this);
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
     * Get available menu.
     * @return array
     */
    public function getMenus()
    {
        if ($this->_normalizeMenus === null) {
            $mid = '/' . $this->getUniqueId() . '/';
            // resolve core menus
            $this->_normalizeMenus = [];

            $config = components\Configs::instance();
            $conditions = [
                'user' => $config->db && $config->db->schema->getTableSchema($config->userTable),
                'assignment' => ($userClass = Yii::$app->getUser()->identityClass) && is_subclass_of($userClass, 'yii\db\BaseActiveRecord'),
                'menu' => $config->db && $config->db->schema->getTableSchema($config->menuTable),
            ];
            foreach ($this->_coreItems as $id => $lable) {
                if (!isset($conditions[$id]) || $conditions[$id]) {
                    $this->_normalizeMenus[$id] = ['label' => Yii::t('yiiplus/desktop', $lable), 'url' => [$mid . $id]];
                }
            }
            foreach (array_keys($this->controllerMap) as $id) {
                $this->_normalizeMenus[$id] = ['label' => Yii::t('yiiplus/desktop', Inflector::humanize($id)), 'url' => [$mid . $id]];
            }

            // user configure menus
            foreach ($this->_menus as $id => $value) {
                if (empty($value)) {
                    unset($this->_normalizeMenus[$id]);
                    continue;
                }
                if (is_string($value)) {
                    $value = ['label' => $value];
                }
                $this->_normalizeMenus[$id] = isset($this->_normalizeMenus[$id]) ? array_merge($this->_normalizeMenus[$id], $value)
                    : $value;
                if (!isset($this->_normalizeMenus[$id]['url'])) {
                    $this->_normalizeMenus[$id]['url'] = [$mid . $id];
                }
            }
        }
        return $this->_normalizeMenus;
    }

    /**
     * Set or add available menu.
     * @param array $menus
     */
    public function setMenus($menus)
    {
        $this->_menus = array_merge($this->_menus, $menus);
        $this->_normalizeMenus = null;
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $view = $action->controller->getView();
            $view->params['breadcrumbs'][] = [
                'label' => ($this->defaultUrlLabel ?: Yii::t('yiiplus/desktop', 'Admin')),
                'url' => ['/' . ($this->defaultUrl ?: $this->uniqueId)],
            ];
            return true;
        }
        return false;
    }
}
