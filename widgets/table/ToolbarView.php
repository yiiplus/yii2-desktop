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

namespace yiiplus\desktop\widgets\table;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * ToolbarView
 *
 * @author liguangquan <liguangquan@163.com>
 * @since 2.0.0
 */
class ToolbarView extends Widget
{
    /**
     * @var string call a bootstrap table with id table via JavaScript.
     */
    public $tableId;

    /**
     * @var array html options to be applied to the toolbar.
     * @since 2.0.4
     */
    public $options = ['id' => 'toolbar'];

    /**
     * @var string the ID of the controller that should handle the actions specified here.
     * If not set, it will use the currently active controller. This property is mainly used by
     * [[urlCreator]] to create URLs for different actions. The value of this property will be prefixed
     * to each action name to form the route of the action.
     */
    public $controller;

    /**
     * @var string the template used for composing each cell in the action column.
     * Tokens enclosed within curly brackets are treated as controller action IDs (also called *button names*
     * in the context of action column). They will be replaced by the corresponding button rendering callbacks
     * specified in [[buttons]]. For example, the token `{create}` will be replaced by the result of
     * the callback `buttons['create']`. If a callback cannot be found, the token will be replaced with an empty string.
     */
    public $template = '';

    /**
     * @var array button rendering callbacks. The array keys are the button names (without curly brackets),
     * and the values are the corresponding button rendering callbacks. The callbacks should use the following
     * signature:
     *
     * ```php
     * function ($url) {
     *     // return the button HTML code
     * }
     * ```
     */
    public $buttons = [];

    /** @var array visibility conditions for each button. The array keys are the button names (without curly brackets),
     * and the values are the boolean true/false or the anonymous function. When the button name is not specified in
     * this array it will be shown by default.
     * The callbacks must use the following signature:
     *
     * ```php
     * function() {
     *     // return boolean value
     * }
     * ```
     *
     * Or you can pass a boolean value:
     *
     * ```php
     * [
     *     'update' => \Yii::$app->user->can('update'),
     * ],
     * ```
     */
    public $visibleButtons = [];

    /**
     * @var callable a callback that creates a button URL using the specified model information.
     * The signature of the callback should be the same as that of [[createUrl()]]
     * Since 2.0.10 it can accept additional parameter, which refers to the column instance itself:
     *
     * ```php
     * function (string $action) {
     *     //return string;
     * }
     * ```
     *
     * If this property is not set, button URLs will be created using [[createUrl()]].
     */
    public $urlCreator;

    /**
     * Initializes the view.
     */
    public function init()
    {
        parent::init();
        $this->initDefaultButtons();
    }

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        // 新增
        if (!isset($this->buttons['create']) && strpos($this->template, '{create}') !== false) {
            $this->buttons['create'] = function ($url) {
                $title  = Yii::t('yiiplus/desktop', '新增');
                $button = "<button id='create' type='button' class='btn btn-info'><i class='glyphicon glyphicon-plus'></i> ${title}</button>";
                return Html::a($button, $url, array_merge(['data-pjax' => '0', 'title' => $title, 'aria-label' => $title]));
            };
        }

        // 移到废纸篓
        if (!isset($this->buttons['trash']) && strpos($this->template, '{trash}') !== false) {
            $this->buttons['trash'] = function ($url) {
                $name = 'trash';

                // 删除 JavaScript 处理
                $view = $this->getView();
                $view->registerJs($this->_deleteJs($name, $url));
                
                // 生成按钮
                $title  = Yii::t('yiiplus/desktop', '移到废纸篓');
                return "<button id='".$name."' type='button' class='btn btn-danger' disabled><i class='glyphicon glyphicon-trash'></i> ${title}</button>";
            };
        }

        // 批量删除
        if (!isset($this->buttons['delete']) && strpos($this->template, '{delete}') !== false) {
            $this->buttons['delete'] = function ($url) {
                $name = 'remove';
                
                // 删除 JavaScript 处理
                $view = $this->getView();
                $view->registerJs($this->_deleteJs($name, $url));
                
                // 生成按钮
                $title  = Yii::t('yiiplus/desktop', '批量删除');
                return "<button id='".$name."' type='button' class='btn btn-danger' disabled><i class='glyphicon glyphicon-remove'></i> ${title}</button>";
            };
        }
    }

    /**
     * 批量删除操作 JS 脚本
     *
     * @param  String $button 按钮名
     * @param  String $url    请求地址
     *
     * @return String         操作脚本
     */
    private function _deleteJs($button, $url)
    {
        return "
            $('#".$this->tableId."').on('check.bs.table uncheck.bs.table ' + 'check-all.bs.table uncheck-all.bs.table', function() {
                $('#".$button."').prop('disabled', !$('#".$this->tableId."').bootstrapTable('getSelections').length)
            })

            $('#".$button."').click(function() {
                var ids = $.map($('#".$this->tableId."').bootstrapTable('getSelections'), function (row) {
                    return row.id
                })

                if(ids.length === 0) {
                    toastr.warning('" . Yii::t('yiiplus/desktop', '请选择要删除的记录') . "')
                    return
                }

                $.ajax({
                    type: 'POST',
                    url: '${url}',
                    data: {
                        ids : ids
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        if(data.success) {
                            toastr.success('" . Yii::t('yiiplus/desktop', '删除成功') . "')
                        } else {
                            toastr.error(data.data.message)
                        }

                        $('#".$button."').prop('disabled', true)
                        $('#".$this->tableId."').bootstrapTable('refresh', {silent:true})
                    },
                    error: function() {
                        toastr.error('" . Yii::t('yiiplus/desktop', '删除失败') . "')
                    }
                });
            })
        ";
    }
 
     /**
     * Creates a URL for the given action and model.
     * 
     * @param string $action the button name (or action ID)
     * 
     * @return string the created URL
     */
    public function createUrl($action)
    {
        if (is_callable($this->urlCreator)) {
            return call_user_func($this->urlCreator, $action, $this);
        }
        $params = $this->controller ? $this->controller . '/' . $action : $action;
        return Url::toRoute($params);
    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        $buttons = preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) {
            $name = $matches[1];
            if (isset($this->visibleButtons[$name])) {
                $isVisible = $this->visibleButtons[$name] instanceof \Closure
                    ? call_user_func($this->visibleButtons[$name])
                    : $this->visibleButtons[$name];
            } else {
                $isVisible = true;
            }

            if ($isVisible && isset($this->buttons[$name])) {
                $url = $this->createUrl($name);
                return call_user_func($this->buttons[$name], $url);
            }

            return '';
        }, $this->template);
        
        return Html::tag('div', $buttons, $this->options);
    }
}
