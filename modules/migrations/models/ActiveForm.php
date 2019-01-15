<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16/7/15
 * Time: 下午11:00
 */

namespace yiiplus\desktop\modules\migrations\models;

use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\helpers\Url;

class ActiveForm extends \yii\widgets\ActiveForm
{
    public $fieldClass = 'yiiplus\desktop\modules\migrations\models\ActiveField';
    public $boxFieldClass = 'yiiplus\desktop\modules\migrations\models\BoxField';

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
     * Generates a form field.
     * A form field is associated with a model and an attribute. It contains a label, an input and an error message
     * and use them to interact with end users to collect their inputs for the attribute.
     * @param Model $model the data model.
     * @param string $attribute the attribute name or expression. See [[Html::getAttributeName()]] for the format
     * about attribute expression.
     * @param array $options the additional configurations for the field object. These are properties of [[ActiveField]]
     * or a subclass, depending on the value of [[fieldClass]].
     * @return ActiveField the created ActiveField object.
     * @see fieldConfig
     */
    public function field($model, $attribute, $options = [])
    {
        return parent::field($model, $attribute, $options);
    }
}