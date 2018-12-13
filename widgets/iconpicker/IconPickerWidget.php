<?php
/**
 * yiiplus\desktop
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    liguangquan@163.com
 * @copyright 2006-2018 YiiPlus Ltd
 * @link      http://www.yiiplus.com
 */

namespace yiiplus\desktop\widgets\iconpicker;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 * icon小图标生成器
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    liguangquan@163.com
 * @copyright 2006-2018 YiiPlus Ltd
 * @link      http://www.yiiplus.com
 */
class IconPickerWidget extends InputWidget
{
    /**
     * @var 允许的变体值
     * glyphicon|ionicon|fontawesome|weathericon|mapicon|octicon|typicon|elusiveicon|materialdesign
     */
    public $iconset = 'fontawesome';

    /**
     * @var 默认class
     */
    public $pickerOptions = ['class' => 'btn btn-default btn-sm'];

    /**
     * @var 列表附加选项
     */
    public $containerOptions = [];

    /**
     * @var 客户端选项
     * @see http://victor-valencia.github.io/bootstrap-iconpicker/
     **/
    public $clientOptions = [];
    
    /**
     * @var JsExpression $onSelectIconCallback
     * @example
     * onSelectIconCallback=>new JsExpression('function(e){
     *    var icon = e.icon;
     *    icon = "some "+icon;
     *    $('#target').val(icon);
     * }'),
     */
    public $onSelectIconCallback;

    /**
     * @var
     */
    private $_id;

    /**
     * 默认值
     * @var string
     */
    private $_default = '';

    public function init()
    {
        if (!isset($this->options['id']) && !$this->hasModel()) {
            $this->options['id'] = 'iconpicker_' . $this->getId();
        }
        parent::init();
        $this->_id = $this->options['id'];
        if ($this->hasModel() && !empty($this->model->{$this->attribute})) {
            $this->_default = $this->pickerOptions['data-icon'] = $this->model->{$this->attribute};
        }
        if (!$this->hasModel() && !empty($this->value)) {
            $this->_default = $this->pickerOptions['data-icon'] = $this->value;
        }
        if (!isset($this->pickerOptions['id'])) {
            $this->pickerOptions['id'] = $this->_id . '_jspicker';
        }
        $this->clientOptions = ArrayHelper::merge($this->getDefaultOptions(), $this->clientOptions);
        $this->registerAssets();
    }

    /**
     * 默认js插件选项
     *
     * @return array
     **/
    protected function getDefaultOptions()
    {
        return [
            'iconset'         => $this->iconset,
            'rows'            => 5,
            'cols'            => 10,
            'columns'         => 10,
            'placement'       => 'right',
            'align'           => 'center',
            'arrowClass'      => 'btn-primary',
            'header'          => true,
            'footer'          => true,
            'labelHeader'     => '{0} / {1}',
            'labelFooter'     => '{0} - {1}:[{2}]',
            'search'          => true,
            'searchText'      => 'SEARCH_ICON',
            'selectedClass'   => 'btn-warning',
            'unselectedClass' => 'btn-default',
        ];
    }
    
    /**
     * 加载器
     */
    public function registerAssets()
    {
        $view = $this->getView();
        $targetId = $this->_id;
        $iconPickerId = $this->pickerOptions['id'];
        IconPickerAsset::register($this->view);
        $this->clientOptions = ArrayHelper::merge($this->clientOptions, [
            'icon' => $this->_default,
        ]);
        $this->clientOptions = Json::encode($this->clientOptions);
        $js[] = <<<JS
           $("#{$iconPickerId}").iconpicker({$this->clientOptions});
JS;
        $callback = null;
        if (!empty($this->onSelectIconCallback)) {
            $callback = $this->onSelectIconCallback instanceof JsExpression
                ? $this->onSelectIconCallback->__toString()
                : $this->onSelectIconCallback;
        }
        $js[] = ($callback)
            ? <<<JS
           $("#{$iconPickerId}").on('change', function(e) {
                var callback = {$callback};
                callback(e);
            });
JS
            :
            <<<JS
            $("#{$iconPickerId}").on('change', function(e) {
                $('#$targetId').val(e.icon);
            });
JS;
        $view->registerJs(implode("\n", $js));
    }

    /**
     * 自动执行
     *
     * @return string bootstrap-picker button with hiddenInput field where we put selected value
     */
    public function run()
    {
        if ($this->hasModel()) {
            $inp = Html::activeHiddenInput($this->model, $this->attribute, $this->options);
        } else {
            $inp = Html::hiddenInput($this->name, $this->value, $this->options);
        }
        $picker = Html::button('CHOOSE_ICON', $this->pickerOptions);
        return Html::tag('div', $picker . $inp, $this->containerOptions);
    }
}