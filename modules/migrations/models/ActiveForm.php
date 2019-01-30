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

namespace yiiplus\desktop\modules\migrations\models;

use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\helpers\Url;

/**
 * 动态表单
 *
 * @author zhangxu <zhangxu@mocaapp.com>
 * @since 2.0.0
 */
class ActiveForm extends \yii\widgets\ActiveForm
{
    /**
     * 动态域
     *
     * @var string
     */
    public $fieldClass = 'yiiplus\desktop\modules\migrations\models\ActiveField';

    /**
     * 多选框
     *
     * @var string
     */
    public $boxFieldClass = 'yiiplus\desktop\modules\migrations\models\BoxField';

    /**
     * 初始化
     */
    public function init()
    {
        parent::init();
        if (!isset($this->validationUrl)) {
            if (!empty($this->action)) {
                $this->validationUrl = ArrayHelper::merge((array)$this->action, ['ajax-validate' => 1]);
            } else {
                $this->validationUrl = Url::current(['ajax-validate' => 1]);
            }
        }
    }

    /**
     * 可折叠
     * @param $model
     * @param $attribute
     * @param array $options
     * @return object
     */
    public function boxField($model, $attribute, $options = [])
    {
        $config = $this->fieldConfig;
        if ($config instanceof \Closure) {
            $config = call_user_func($config, $model, $attribute);
        }
        if (!isset($config['class'])) {
            $config['class'] = $this->boxFieldClass;
        }
        return \Yii::createObject(ArrayHelper::merge($config, $options, [
            'model' => $model,
            'attribute' => $attribute,
            'form' => $this,
        ]));
    }

    /**
     * 格式化输出
     *
     * @param $model
     * @param $attribute
     * @param array $options
     * @return mixed
     */
    public function field($model, $attribute, $options = [])
    {
        return parent::field($model, $attribute, $options);
    }
}